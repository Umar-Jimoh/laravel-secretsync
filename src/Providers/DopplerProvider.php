<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use UmarJimoh\SecretSync\Interfaces\SecretProviderInterface;

class DopplerProvider implements SecretProviderInterface
{
    public function getSecrets(): array
    {
        $config = config('secretsync.doppler');
        $debug = config('secretsync.debug');

        try {
            $query = [];

            if (empty($config['token'])) {
                throw new \RuntimeException("DOPPLER_TOKEN is not set.");
            }

            // project/config are optional, only add if set
            if (!empty($config['project'])) {
                $query['project'] = $config['project'];
            }
            if (!empty($config['config'])) {
                $query['config'] = $config['config'];
            }

            $response = Http::withToken($config['token'])
                ->acceptJson()
                ->get($config['endpoint'] ?? 'https://api.doppler.com/v3/configs/config/secrets', $query);

            $responseBody = json_decode($response->body(), true);

            if ($debug && $error = $this->checkResponseForErrors($responseBody)) {
                return ['debug' => $error];
            }

            $secrets = [];
            foreach ($responseBody['secrets'] ?? [] as $key => $secret) {
                $secrets[strtoupper($key)] = $secret['computed'] ?? $secret['raw'] ?? null;
            }

            return $secrets;
        } catch (Exception $e) {
            if ($debug) {
                return ['debug' => print_r($e->getMessage(), true)];
            }
            return [];
        }
    }

    public function checkResponseForErrors(array $response): array|bool
    {
        // Doppler returns "success": false with error details on failure
        if (isset($response['success']) && $response['success'] === false) {
            return [
                'message' => $response['messages'][0] ?? 'Unknown Doppler error occurred.',
                'details' => 'Full response: ' . json_encode($response),
            ];
        }

        if (empty($response['secrets'])) {
            return [
                'message' => $response['message'] ?? 'No secrets returned from Doppler.',
                'details' => 'The Doppler configuration might be empty or inaccessible.',
            ];
        }

        return false;
    }

    public function name(): string
    {
        return 'Doppler';
    }
}

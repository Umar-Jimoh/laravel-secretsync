<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use UmarJimoh\SecretSync\Interfaces\SecretProviderInterface;

class InfisicalProvider implements SecretProviderInterface
{
    public function getSecrets(): array
    {
        $config = config('secretsync.infisical');
        $debug = config('secretsync.debug');

        try {
            $response = Http::withToken($config['token'])->get($config['api_endpoint'] ?? '', [
                'environment' => $config['env'],
                'workspaceId' => $config['workspace_id'],
            ]);

            $responseBody = json_decode($response->body(), true);

            if ($debug && $error = $this->checkResponseForErrors($responseBody)) {
                return ['debug' => $error];
            }

            $secrets = [];
            foreach ($response->json()['secrets'] ?? [] as $secret) {
                $secrets[strtoupper($secret['secretKey'])] = $secret['secretValue'];
            }

            return $secrets;
        } catch (Exception $e) {
            if ($debug) {
                return ['debug' => print_r($e->getMessage(), true)];
            }
            return [];
        }
    }

    public function checkResponseForErrors(array $response): array|Bool
    {
        if (isset($response['statusCode']) && $response['statusCode'] >= 400) {
            return [
                'message' => $response['message'] ?? 'Unknown error occurred.',
                'details' => 'Request ID: ' . ($response['reqId'] ?? 'N/A') . ', Error: ' . ($response['error'] ?? 'N/A'),
            ];
        }

        if (empty($response['secrets'])) {
            return [
                'message' => $response['message'] ?? 'The specified environment slug is either incorrect or exists but contains no secrets on Infisical. Please double-check the "INFISICAL_ENV" value and ensure it is not empty.',
                'details' => 'No additional error info provided.',
            ];
        }
        return false;
    }

    public function name(): string
    {
        return 'Infisical';
    }
}

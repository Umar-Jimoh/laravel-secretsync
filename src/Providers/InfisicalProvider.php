<?php

namespace UmarJimoh\SecretSync\Providers;

use Exception;
use Illuminate\Support\Facades\Http;
use UmarJimoh\SecretSync\Caches\SecretCache;
use UmarJimoh\SecretSync\Interfaces\SecretProviderInterface;

class InfisicalProvider implements SecretProviderInterface
{
    public function getSecrets(): array
    {
        $cache = app(SecretCache::class);
        $cacheSecrets = $cache->get();

        if (!empty($cacheSecrets)) {
            return $cacheSecrets;
        }

        $config = config('secretsync.infisical');

        try {
            $response = Http::withToken($config['token'])->get($config['api_endpoint'], [
                'environment' => $config['env'],
                'workspaceId' => $config['workspace_id'],
            ]);

            if (!$response->successful()) {
                throw new Exception();
            }

            $secrets = [];
            foreach ($response->json()['secrets'] ?? [] as $secret) {
                $secrets[strtoupper($secret['secretKey'])] = $secret['secretValue'];
            }

            $cache->store($secrets);

            return $secrets;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function name(): string
    {
        return 'Infisical';
    }
}

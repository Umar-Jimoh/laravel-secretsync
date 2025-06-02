<?php

declare(strict_types=1);

namespace UmarJimoh\SecretSync\Caches;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SecretCache
{
    public function get(): array
    {
        try {
            if (Cache::has('secretsync_data')) {
                return Crypt::decrypt(Cache::get('secretsync_data'));
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function store($secrets)
    {
        try {
            if (config('secretsync.cache.enabled')) {
                $driver = config('secretsync.cache.driver') ?? config('cache.default');
                $cache = Cache::store($driver);
                $cache->put('secretsync_data', Crypt::encrypt($secrets), now()->addSeconds(config('secretsync.cache.ttl', 300)));
            }
        } catch (\Exception $e) {
            return ['warning' => "Failed to store synced secrets: Either database does not exist or No application encryption key has been specified"];
        }
    }
}

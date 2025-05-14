<?php

namespace UmarJimoh\SecretSync\Caches;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SecretCache
{
    public function get(): array
    {
        if (Cache::has('secretsync_data')) {
            return Crypt::decrypt(Cache::get('secretsync_data'));
        }

        return [];
    }

    public function store($secrets): Void
    {
        if (config('secretsync.cache.enabled')) {
            $driver = config('secretsync.cache.driver') ?? config('cache.default');
            $cache = Cache::store($driver);
            $cache->put('secretsync_data', Crypt::encrypt($secrets), now()->addSeconds(config('secretsync.cache.ttl')) ?? 600);
        }
    }
}

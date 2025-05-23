<?php

namespace UmarJimoh\SecretSync\Caches;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use UmarJimoh\SecretSync\Helpers\AppKeyValidator;

class SecretCache
{
    public function get(): array
    {
        $key = (new AppKeyValidator)->checkAppKey();

        if (isset($key['error'])) {
            return $key;
        }
        
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
            $cache->put('secretsync_data', Crypt::encrypt($secrets), now()->addSeconds(config('secretsync.cache.ttl')) ?? 300);
        }
    }
}

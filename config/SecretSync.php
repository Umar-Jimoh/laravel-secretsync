<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Secret Sync Provider
    |--------------------------------------------------------------------------
    |
    | This option controls which secrets provider should be used when 
    | synchronizing secrets. You can define and switch between multiple 
    | providers such as "infisical", etc., using the environment variable.
    |
    */

    'provider' => env('SECRETSYNC_PROVIDER'),

    /*
    |--------------------------------------------------------------------------
    | Secret Sync Caching
    |--------------------------------------------------------------------------
    |
    | If enabled, retrieved secrets will be cached to avoid repeated
    | requests to the secrets provider. You can configure the time-to-live 
    | (ttl) in seconds and the Laravel-supported cache driver to use.
    |
    */
    
    'cache' => [
        'enabled' => env('SECRETSYNC_CACHE', true),
        'ttl' => env('SECRETSYNC_CACHE_TTL', 300), // default 5 mins
        'driver' => env('SECRETSYNC_CACHE_DRIVER', config('cache.default')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Infisical Provider Configuration
    |--------------------------------------------------------------------------
    |
    | If you're using the "infisical" provider, you can configure it here.
    | These values will be used when connecting to the Infisical API 
    | to retrieve your secrets.
    |
    */

    'infisical' => [
        'token' => env('INFISICAL_TOKEN', 'token'),
        'api_endpoint' => env('INFISICAL_API_ENDPOINT', 'endpoint'),
        'env' => env('INFISICAL_ENV', 'enviroment'),
        'workspace_id' => env('INFISICAL_WORK_ID', 'workId'),
    ],
];

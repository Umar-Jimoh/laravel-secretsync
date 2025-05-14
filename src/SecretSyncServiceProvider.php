<?php

namespace UmarJimoh\SecretSync;

use Illuminate\Support\ServiceProvider;

class SecretSyncServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/secretsync.php', 'secretsync');

        $this->app->singleton('secretsync', function () {
            return new SecretSync();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/secretsync.php' => config_path('secretsync.php'),
        ], 'config');

        $this->commands([
            \UmarJimoh\SecretSync\Commands\SecretSync::class,
        ]);
    }
}

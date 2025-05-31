<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync;

use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\ServiceProvider;

class SecretSyncServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/secretsync.php', 'secretsync');

        $this->app->singleton('secretsync', function () {
            return new SecretSync();
        });
    }

    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/secretsync.php' => config_path('secretsync.php'),
        ], 'config');

        \Illuminate\Support\Facades\Event::listen(CommandStarting::class, function(CommandStarting $event) {
            if(in_array($event->command, ['optimize'])) {
                echo "\n⚠️  Secrets may be out of sync. Please run `php artisan secretsync` after `{$event->command}` to refresh them.\n";
            }
        });

        $this->commands([
            \UmarJimoh\SecretSync\Commands\SecretSync::class,
        ]);
    }
}

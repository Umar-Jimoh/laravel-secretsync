<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync\Commands;

use Illuminate\Console\Command;
use UmarJimoh\SecretSync\Providers\InfisicalProvider;
use UmarJimoh\SecretSync\Facades\SecretSync as FacadeSecretSync;

class SecretSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secretsync
                            {--debug : Gives more information about the error that occurred}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Secrets from secret manager';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $debug = $this->option('debug');

        if ($debug) {
            config(['secretsync.debug' => true]);
        }

        $providerKey = config('secretsync.provider');
        $provider = match ($providerKey) {
            'infisical' => new InfisicalProvider(),
            default => null,
        };

        if ($provider) {
            $secrets = FacadeSecretSync::secretSyncService($provider);

            if (isset($secrets['error']) && !is_string($secrets['error'])) {
                $this->components->error(ucfirst($providerKey));
                foreach ($secrets['error'] as $error => $value) {
                    $error = strtoupper($error);
                    $this->line("  <fg=red;options=bold>$error:</> {$value}");
                    $this->newLine();
                }
                return self::FAILURE;
            }

            if (isset($secrets['error'])) {
                return $this->fail($secrets['error']);
            }

            $this->components->info('Secrets synced successfully!');
        } else {
            $this->fail('No valid provider configured.');
        }
    }
}

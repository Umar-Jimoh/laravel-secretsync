<?php

namespace UmarJimoh\SecretSync\Commands;

use Illuminate\Console\Command;
use UmarJimoh\SecretSync\Providers\InfisicalProvider;
use UmarJimoh\SecretSync\facades\SecretSync as FacadeSecretSync;

class SecretSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secretsync';

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
        $providerKey = config('secretsync.provider');
        $provider = match ($providerKey) {
            'infisical' => new InfisicalProvider(),
            default => null,
        };

        if ($provider) {
            $sync = FacadeSecretSync::syncFromProvider($provider);

            if (isset($sync['error'])) {
                
                $this->fail($sync['error']);
            } else {
                $this->callSilently('config:cache');

                $this->components->info('Secrets synced successfully!'); 
            }
        } else {
            $this->fail('No valid provider configured.');
        }
    }
}

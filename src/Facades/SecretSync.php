<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync\facades;

use Illuminate\Support\Facades\Facade;

class SecretSync extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'secretsync';
    }
}
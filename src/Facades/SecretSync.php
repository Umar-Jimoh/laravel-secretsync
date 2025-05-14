<?php
namespace UmarJimoh\SecretSync\facades;

use Illuminate\Support\Facades\Facade;

class SecretSync extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'secretsync';
    }
}
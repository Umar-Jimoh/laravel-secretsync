<?php

namespace UmarJimoh\SecretSync\Helpers;

class SecretMapper
{
    public function map(array $secrets): void
    {
    foreach ($secrets as $key => $value) {
        putenv("$key=$value");
        $_ENV[$key] = $_SERVER[$key] = $value;
        }
    }
}

<?php

namespace UmarJimoh\SecretSync\Interfaces;

interface SecretProviderInterface 
{
    public function getSecrets(): array;
    public function name(): string;
}
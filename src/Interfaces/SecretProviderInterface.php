<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync\Interfaces;

interface SecretProviderInterface
{
    public function getSecrets(): array;
    public function name(): string;
    public function checkResponseForErrors(array $response): array|Bool;
}

<?php
declare(strict_types=1);

namespace UmarJimoh\SecretSync\Helpers;

use Illuminate\Support\Str;

class AppKeyValidator
{
    public function checkAppKey()
    {
        try {
            $appKey = config('app.key');

            if (empty($appKey)) {
                throw new \Exception('APP_KEY is not set. Please define it in your .env file.');
            }

            $decoded = base64_decode(Str::after($appKey, 'base64:'), true);

            if ($decoded === false || strlen($decoded) !== 32) {
                throw new \Exception("APP_KEY is not a valid base64-encoded 32-byte key.");
            } 
            return true;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
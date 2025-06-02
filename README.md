<p align="center">
<img src="./docs/secretsync.png" height="80" alt="SecretSync Logo">
<p align="center">
        <!-- <a href="https://github.com/umar-jimoh/laravel-secretsync/actions"><img alt="GitHub Workflow Status (master)" src="https://github.com/umar-jimoh/laravel-secretsync/actions/workflows/tests.yml/badge.svg"></a> -->
        <a href="https://packagist.org/packages/umar-jimoh/laravel-secretsync"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/umar-jimoh/laravel-secretsync"></a>
        <a href="https://packagist.org/packages/umar-jimoh/laravel-secretsync"><img alt="Latest Version" src="https://img.shields.io/packagist/v/umar-jimoh/laravel-secretsync"></a>
        <a href="https://packagist.org/packages/umar-jimoh/laravel-secretsync"><img alt="License" src="https://img.shields.io/packagist/l/umar-jimoh/laravel-secretsync"></a>
    </p>
</p>

## Introduction
This package allows you to easily sync secrets from secret managers into your Laravel app. Currently, only <strong>Infisical</strong> is supported.

Once installed you can do stuff like this:
``` bash
php artisan secretsync
```
## 📦 Installation
You can install the package via composer:
```bash
composer require umar-jimoh/laravel-secretsync
```
Publish Config:
```bash
php artisan vendor:publish --provider="UmarJimoh\SecretSync\SecretSyncServiceProvider" --tag=config
```

## 🧪 Usage
Before syncing secrets, ensure you've properly set up your secret manager (e.g., Infisical) and provided the necessary credentials or identifiers in your .env file.
```bash
SECRETSYNC_PROVIDER="infisical"
INFISICAL_API_ENDPOINT=
INFISICAL_TOKEN=
INFISICAL_ENV=
INFISICAL_WORK_ID="your-infisical-project-id"
```

>**_NOTE_**: Make sure to include and fetch `APP_KEY` from your secret manager. It must not be defined in the `.env` file to avoid conflicts and to ensure proper Laravel encryption.

Once the package is installed, you can sync secrets by running:
```bash
php artisan secretsync
``` 
This command fetches and applies secrets from your configured provider into your Laravel application.

You can enable caching so that secrets are stored and retrieved locally instead of fetching them from the provider every time. To enable this, configure the following in your .env file:
```bash
SECRETSYNC_CACHE=true
SECRETSYNC_CACHE_TTL=300   # (in seconds)
SECRETSYNC_CACHE_DRIVER=   # (optional, defaults to Laravel's default cache driver)
```
Secrets are securely encrypted using Laravel's cache driver.

You may also define these values in `config/secretsync.php`.


If you encounter issues during sync, use the `--debug` flag for more detailed error messages:
```bash
php artisan secretsync --debug
```
Alternatively, enable debugging via .env:
```bash
SECRETSYNC_DEBUG=true
```

> **_NOTE:_** In production if you run `php artisan optimize` make sure you run `php artisan secretsync` afterward to ensure secrets are properly synced. 

## 🤝 Contributing
Feel free to open issues or pull requests to improve the package. I welcome contributions that help make this package better!

## 📧 Contact
If you have any questions, feel free to reach out to me at umarjimoh@hotmail.com or via **[Twitter](https://x.com/umarjimoh_dev)**.



## 🔗 License
This package is open-source software licensed under the . **[MIT License](https://github.com/Umar-Jimoh/laravel-secretsync/blob/main/LICENSE)**.


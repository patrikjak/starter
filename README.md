# Starter

[![codecov](https://codecov.io/gh/patrikjak/starter/graph/badge.svg?token=kaq2yLG9xq)](https://codecov.io/gh/patrikjak/starter)

## Installation

Install the package via Composer:

```bash
composer require patrikjak/starter
```

## Documentation

- [Web header Components](docs/metadata-header.md) - Documentation for the web header component
- [Configuration](docs/config.md) - Documentation for all available configuration options

## Setup

After installing the package, add the package provider to the providers array in bootstrap/providers.php.

```php
use Patrikjak\Starter\StarterServiceProvider;
use Patrikjak\Utils\UtilsServiceProvider;
use Patrikjak\Auth\AuthServiceProvider;

return [
    ...
    UtilsServiceProvider::class,
    AuthServiceProvider::class,
    StarterServiceProvider::class,
];
```

You need to have installed and configured `patrikjak/utils` and `patrikjak/auth` packages.

For fast setup, you can run this command:

```bash
php artisan install:pjstarter
```

It will publish assets, views and config file.

If you don't publish config file, you will miss all features of this package. I recommend add this script to your `composer.json` file:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=pjstarter-assets --ansi --force",
        "@php artisan vendor:publish --tag=pjstarter-config --ansi --force",
        "@php artisan vendor:publish --tag=pjstarter-views --ansi --force",
        "@php artisan vendor:publish --tag=pjstarter-migrations --ansi --force"
    ]
}
```

All post-update-cmd can look like this:

```json
"scripts": {
    "post-update-cmd": [
        "@php artisan vendor:publish --tag=pjutils-config --ansi --force",
        "@php artisan vendor:publish --tag=pjutils-assets --ansi --force",
        "@php artisan vendor:publish --tag=pjutils-translations --ansi --force",
        "@php artisan vendor:publish --tag=pjauth-assets --ansi --force",
        "@php artisan vendor:publish --tag=pjauth-config --ansi --force",
        "@php artisan vendor:publish --tag=pjauth-migrations --ansi",
        "@php artisan vendor:publish --tag=pjstarter-assets --ansi --force",
        "@php artisan vendor:publish --tag=pjstarter-config --ansi --force",
        "@php artisan vendor:publish --tag=pjstarter-views --ansi --force",
        "@php artisan vendor:publish --tag=pjstarter-migrations --ansi --force"
    ]
}
```

Adjust it to your needs. Be aware that --force flag will overwrite existing files.
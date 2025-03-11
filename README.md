# Starter

[![codecov](https://codecov.io/gh/patrikjak/starter/graph/badge.svg?token=kaq2yLG9xq)](https://codecov.io/gh/patrikjak/starter)

## Installation

Install the package via Composer:

```bash
composer require patrikjak/starter
```

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
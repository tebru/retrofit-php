Installation
============

Installation with Composer
--------------------------

```bash
composer require tebru/retrofit-php:~3.0
```

Retrofit does not include an http client, please install an
implementation.

```bash
composer require tebru/retrofit-php-http-guzzle6
```

Retrofit does not support converting request or response bodies outside
PSR-7 `StreamInterface`. Install a converter to handle custom
conversions.

```bash
composer require tebru/retrofit-php-converter-gson
```

Setup
-----

The easiest way to get setup is to add the psr-4 autoload location where
you include the autoloader.

```php
$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Tebru\\Retrofit\\Proxy\\', __DIR__ . '</path/to/cache/dir>/retrofit');
```

If you do not have environment specific cache directories, you could
specify the psr-4 autoload location in your composer.json instead.

```json
"autoload": {
    "psr-4": {
        "Tebru\\Retrofit\\Generated\\": "<path/to/cache/dir>/retrofit"
    }
}
```

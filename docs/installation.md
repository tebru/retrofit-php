Installation
============

Installation with Composer
--------------------------

    composer require tebru/retrofit-php:~2.0

Retrofit does not include an http client, please install guzzle. Additional clients will be supported in the future.

    composer require guzzlehttp/guzzle

Setup
-----

The easiest way to get setup is to add the psr-4 autoload location where you include the autoloader.

```php
$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Tebru\\Retrofit\\Generated\\', __DIR__ . '</path/to/cache/dir>/retrofit');
```

If you do not have environment specific cache directories, you could specify the psr-4 autoload location in your composer.json instead.

```json
    "autoload": {
        "psr-4": {
            "Tebru\\Retrofit\\Generated\\": "<path/to/cache/dir>/retrofit"
        }
    }
```

Use the [command][retrofit command] to generate the cache files.  This will have to be run after every change to a client.

[retrofit command]: usage.md#command

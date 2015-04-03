Installation
============

Installation with Composer
--------------------------

This library is still under development.  Please install from dev-master.

    composer require tebru/retrofit-php:dev-master


Setup
-----

This project requires a PSR autoloader. If your project does not currently 
provide an autoloader, You *may* have to register an autoloader. If one is not 
provided, you can use [composer's][composer autoloader].

You also need to configure your application to load the cache files.  Retrofit creates
all the cache files in a `retrofit` directory in your cache directory.

If you will only have one location that the cache exists, you can do it through composer:

```json
    "autoload": {
        "psr-4": {
            "Tebru\\Retrofit\\Service\\": "path/to/cache/dir/retrofit"
        }
    }
```

If you need to define the cache location at runtime or are already setting up the composer autoloader:

```php
$loader = require __DIR__ . '/vendor/autoload.php';
$loader->addPsr4('Tebru\\Retrofit\\Service\\', __DIR__ . '/path/to/cache/dir/retrofit');
```

Next, create a new `Retrofit` object and specify the cache directory.  Defaults to `/tmp`

```php
$retrofit = new \Tebru\Retrofit\Retrofit(__DIR__ . '/path/to/cache/dir');
```

Register all services with the `Retrofit` object.  These are the interfaces 
you're creating.  This is necessary to generate the cache files.

```php
$retrofit->registerServices([FooService::class, BarService::class]);
$retrofit->registerService(BazService::class);
```

Alternatively, you can use the cacheAll() method to avoid manually specifying
services.  Provide the path to the directory that contains the interfaces.  This
method will recursively search for the interfaces and should not be used in production.

```php
$retrofit->cacheAll(__DIR __ . '/path/to/src/dir');
```

Create the cache.  We need to do this in order to prevent costly class 
generation during runtime.

```php
$retrofit->createCache();
```

Try to avoid creating the cache on every request outside of development.  Instead, use
the [command][retrofit command] to generate the cache files.

[composer autoloader]: https://getcomposer.org/doc/01-basic-usage.md#autoloading
[retrofit command]: usage.md#command

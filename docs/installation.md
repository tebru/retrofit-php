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

Create a new `Retrofit` object and specify the cache directory.  Defaults to `/tmp`

```php
$retrofit = new \Tebru\Retrofit\Retrofit(__DIR__ . '/cache');
```

Register all services with the `Retrofit` object.  These are the interfaces 
you're creating.  This is necessary to generate the cache file.

```php
$retrofit->registerServices([FooService::class, BarService::class]);
$retrofit->registerService(BazService::class);
```

Create the cache.  We need to do this in order to prevent costly class 
generation during runtime.  Passing `false` to this method will only compile the
classes if a cache file does not currently exist.

```php
$retrofit->createCache(true|false);
```

Finally load the classes into memory.

```php
$retrofit->load();
```

[composer autoloader]: https://getcomposer.org/doc/01-basic-usage.md#autoloading

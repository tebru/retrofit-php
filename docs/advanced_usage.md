Advanced Usage
==============

Caching
-------

By default, Retrofit doesn't do any filesystem caching. In production,
you should enable caching to increase performance.

```php
Retrofit::builder()
    ->setCacheDir(__DIR__.'/cache')
    ->enableCache();
```

Custom Converters
-----------------

By default, Retrofit can convert any type to a string, but only handles
PSR-7 `StreamInterface` for request and response bodies. Use a custom
converter to allow handling different types.

```php
Retrofit::builder()
    ->addConverterFactory(new CustomConverterFactory());
```

This should implement `Tebru\Retrofit\ConverterFactory` with three
methods to convert to string, to a stream for a request body, or from a
stream for a response body. Return null from any of them will cause
Retrofit to skip that converter and move on to the next one.

Each method will receive a `TypeToken`, which you can use to determine
the type of the parameter you're converting.

Currently only a Gson converter exists to handle more complex
conversions.

```bash
composer require tebru/retrofit-php-converter-gson
```


Custom Http Client
------------------

Retrofit doesn't provide any way to make http requests out of the box.

Currently, Guzzle 6 is the only supported library.

```bash
composer require tebru/retrofit-php-http-guzzle6
```


```php
Retrofit::builder()
    ->setHttpClient(new Guzzle6HttpClient(new Client()));
```

This implements `Tebru\Retrofit\HttpClient` which has methods to make
requests synchronously and asynchronously using PSR-7 request objects.

Custom Call Adapters
--------------------

The default call adapter doesn't modify the Call at all. Implement a
`Tebru\Retrofit\CallAdapter` and `Tebru\Retrofit\CallAdapterFactory`
if you want to change the return type from service methods.

This supporting library doesn't exist yet, but if you wanted to use
Retrofit with Rx-PHP, you could do so like this.

```php
/**
 * @GET("/")
 */
public function test(): Observable;
```

```php
Retrofit::builder()
    ->addCallAdapterFactory(new RxCallAdapterFactory());
```

```php
interface CallAdapterFactory
{
    public function supports(TypeToken $type): bool
    {
        return $type->isA(Observable::class);
    }

    public function create(TypeToken $type): CallAdapter
    {
        return new RxCallAdapter();
    }
}
```

The `RxCallAdapter` would then wrap the call in an observable and return
that instead of the normal Call.

Custom Proxy
------------

You may find it necessary to alter the default behavior of sending http
requests. This could be for testing purposes, or perhaps because an
api is not built yet and you need to fetch the data somewhere else.

This can be handled by implementing `Tebru\Retrofit\ProxyFactory` and
`\Tebru\Retrofit\Proxy`.

```php
Retrofit::builder()
    ->addProxyFactory(new CustomProxyFactory());
```

Your proxy should implement the service interface. If you need access to
the default proxy factory, you can implement
`\Tebru\Retrofit\DefaultProxyFactoryAware` which will tell Retrofit to
set the default proxy to your proxy. This is useful if you only need
to override specific methods and want to delegate the rest to the default.

Retrofit PHP
============

[![Build Status](https://travis-ci.org/tebru/retrofit-php.svg?branch=master)](https://travis-ci.org/tebru/retrofit-php)
[![Code Coverage](https://scrutinizer-ci.com/g/tebru/retrofit-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tebru/retrofit-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tebru/retrofit-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tebru/retrofit-php/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d2188bf8-8248-4df6-8bc5-8150fc0b8898/mini.png)](https://insight.sensiolabs.com/projects/d2188bf8-8248-4df6-8bc5-8150fc0b8898)

Retrofit is a type-safe REST client. It is blatantly stolen from
[square/retrofit](https://github.com/square/retrofit) and implemented in
PHP.

❗UPGRADE NOTICE❗
----------------

**Version 3 introduces many breaking changes. Please review the
[upgrade guide](docs/upgrade_2_3.md) before upgrading.**

Overview
--------

*The following is for version 3, please check out the corresponding tag
for version 2 documentation*

Retrofit allows you to define your REST API with a simple interface. The
follow example will attempt to display a typical use-case, but requires
two additional libraries. The first uses Guzzle to make http requests as
Retrofit does not ship with any default way to make network requests. The
second uses a serializer (Gson) to hook into Retrofit's Converter
functionality. This allows for automatic serialization of request bodies
and deserialization of response bodies.

```php
interface GitHubService
{
    /**
     * @GET("/users/{user}/list")
     * @Path("user")
     * @ResponseBody("App\GithubService\ListRepo")
     * @ErrorBody("App\GitHubService\ApiError")
     */
    public function listRepos(string $user): Call;
}
```

Annotations are used to configure the endpoint.
Then, the `Retrofit` class generates a working implementation of the
service interface.

```php
$retrofit = RetrofitBuilder::builder()
    ->setBaseUrl('https://api.github.com')
    ->setHttpClient(new Guzzle6HttpClient(new Client())) // requires a separate library
    ->addConverterFactory(new GsonConverterFactory(Gson::builder()->build())) // requies a separate library
    ->build();
    
$gitHubService = $retrofit->create(GitHubService::class);
```

Our newly created service is capable of making GET requests to
/users/{user}/list, which returns a `Call` object.

```php
$call = $gitHubService->listRepos('octocat');
```

The `Call` object is then used to execute the request synchronously
or asynchronously, returning a response.

```php
$response = $call->execute();

// or

$call->enqueue(
    function(Response $response) { }, // response callback (optional)
    function(Throwable $throwable) { } // error callback (optional)
);
$call->wait();
```

You can then check to see if the request was successful and get the
deserialized response body.

```php
if (!$response->isSuccessful()) {
    throw new ApiException($response->errorBody());
}

$responseBody = $response->body();
```

*Usage examples are referenced from Square's documentation*


Installation & Usage
--------------------

*Retrofit 3 requires PHP 7.1*

```bash
composer require tebru/retrofit-php
```

Please make sure you also install an http client.

```bash
composer require tebru/retrofit-php-http-guzzle6
```

Install a converter to handle more advanced request and response body
conversions.

```bash
composer require tebru/retrofit-php-converter-gson
```

### Documentation 

- [Installation](docs/installation.md)
- [Getting Started](docs/usage.md)
- [Advanced Usage](docs/advanced_usage.md)
- [Annotation Reference](docs/annotations.md)

License
-------

This project is licensed under the MIT license. Please see the `LICENSE` file
for more information.

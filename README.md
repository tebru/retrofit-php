[![Build Status](https://travis-ci.org/tebru/retrofit-php.svg?branch=master)](https://travis-ci.org/tebru/retrofit-php)

# Retrofit PHP
This library aims to ease creation of REST clients.  It is blatantly stolen from square/retrofit (https://github.com/square/retrofit) and implemented in PHP.

## Installation
This library is still under development.  Please install from dev-master.

```
composer require tebru/retrofit-php:dev-master
```

## Setup
You *may* have to register an autoloader. If one is not provided, you can use composers.

```
$loader = require 'path/to/vendor/autoload.php'
AnnotationRegistry::registerLoader([$loader, 'loadClass'])
```

Create a new `Retrofit` object and specify the cache directory.  Defaults to `/tmp`

```
$retrofit = new \Tebru\Retrofit\Retrofit(__DIR__ . '/cache');
```

Register all services with the `Retrofit` object.  These are the interfaces you're creating.  This is necessary to generate the cache file.

```
$retrofit->registerServices([FooService::class, BarService::class]);
$retrofit->registerService(BazService::class);
```

Create the cache.  We need to do this in order to prevent costly class generation during runtime.  Passing `false` to this method will only compile the classes if a cache file does not currently exist.

```
$retrofit->createCache(true|false);
```

Finally load the classes into memory.

```
$retrofit->load();
```

### Warming the Cache
You can use the console command (likely in `vendor/bin/retrofit`) to generate the cache file.  In some development/deployment workflows, all of the above setup could be bypassed using the command to generate the cache file.  The only requirement after the cache is generated is to require the file, which is what `load()` does.

```
path/to/retrofit compile source/directory cache/directory
```

Source and cache directory are both relative to the directory you're executing the command from

## Usage Overview
*Usage examples are referenced from Square's documentation*

Retrofit allows you to define your REST API through annotations.

```
<?php

use Tebru\Retrofit\Annotation as Rest;

interface GitHubService
{
    /**
     * @Rest\GET("/users/{user}/list")
     * @Rest\Returns("ArrayCollection<ListRepo>")
     */
    public function listRepos($user);
}
```

```
<?php

use Tebru\Retrofit\Adapter\RestAdapter;

$restAdapter = RestAdapter::builder()
    ->setBaseUrl('https://api.github.com')
    ->build();
    
$gitHubService = $restAdapter->create(GitHubService::class);
```

This means that the REST client will make a GET request to /users/$user/list and return an ArrayCollection of ListRepo objects.

```
$repos = $gitHubService->listRepos('octocat');
```

## API
Annotations determine how the REST client will function

### Request Method
Each method must have a request method defined.  `GET`, `POST`, `PUT`, `DELETE`, `HEAD`, `PATCH`, and `OPTIONS` come out of the box.

### URL Manipulation
As seen before, parameters can be defined by inserting curly braces into the url path.

```
/**
 * @GET("/users/{user}/list")
 */
public function listRepos($user);
```

The are mapped automatically to parameters on the method.

### Query Parameters
Query parameters can be added to the url

```
@GET("/users/{user}/list?sort=desc")
```

Or as an annotation which maps to a method parameter and lets you change the value at runtime using `@Query`.

```
/**
 * @GET("/users/{user}/list")
 * @Query("sort")
 */
public function listRepos($user, $sort);
```

Any annotation that maps to PHP variables can be overriden by passing a `var` key.

```
/**
 * @GET("/users/{user}/list")
 * @Query("sort", var="foo")
 */
public function listRepos($user, $foo);
```

You can also pass in an array of parameters with `@QueryMap`, which also maps to a method parameter.

```
/**
 * @GET("/users/{user}/list")
 * @QueryMap("queryParams")
 */
public function listRepos($user, array $queryParams);
```

Passing `['foo' => 'bar']` to $queryParams will result in a query formatted like `?foo=bar` while passing `['key' => ['foo' => 'bar']]` will result in `?key[foo]=bar`.

### Request Body
Request body also maps to a method parameter.  Acceptable values are `string`, `array`, or an object that can be serialied.

```
/**
 * @GET("/users/{user}/list")
 * @Body("body")
 */
public function listRepos($user, $body);
```

If an array is passed in, the http client will determine the correct method for sending the body (`application/x-www-form-urlencoded` or `multipart/form-data`)

You can also build the body dynamically with `@Part` annotations.  Each annotation maps to a method parameter and will create a body array.

```
/**
 * @GET("/users/{user}/list")
 * @Part("part1")
 * @Part("part2", var="foo")
 */
public function listRepos($user, $part1, $foo);
```

Both `@Body` and `@Part` annotations cannot be set.

### Headers
Headers can be set on the class or method using `@Headers`.  If they're set on the class, they'll be applied to each method.

```
/**
 * @Headers("Accept: application/vnd.github.v3.full+json")
 * @Headers({
 *   "Cache-Control: private, max-age=0, no-cache",
 *   "User-Agent: Retrofit-Sample-App"
 * })
 */
interface GitHubService
{
```

They can also be set individually on methods with `@HEADER` and map to a method parameter.

```
/**
 * @GET("/users/{user}/list")
 * @Header("Accept", var="accept")
 */
public function listRepos($user, $accept);
```

### Returning
Use `@Returns` to specify a return type.  The default is `array`.  Other acceptable values are `raw` or any type specified in the JMS Serializer documentation.  A `raw` return will return the API response as a string.

```
/**
 * @GET("/users/{user}/list")
 * @Returns("ArrayCollection<My\Foo\ReposList>")
 */
public function listRepos($user, $accept);
```

## Events
Add event listeners directly to the guzzle client or serializer, or add a subscriber to the builder.

### Examples of handling request events

```
$httpClient->getEmiter()->on('before', function use ($myHeader) (BeforeEvent $event) {
    $request = $event->getRequest();
    
    $request->getQuery()->add('sort', 'desc');
    $request->addHeader('My-Header', $myHeader);
});
```

```
$httpCient->getEmiter()->on('complete', function (CompleteEvent $event) {
    $responseBody = (string)$event->getResponse()->getBody();
    $responseBody = json_decode($responseBody);
    
    if ('success' !== $responseBody['status'], true) {
        throw new Exception('boo!');
    }
});
```

## Interoperability
The two main technologies backing Retrofit are Guzzle and JMS Serializer.  We aim to provide enough integration points with these libraries so you can use them as you're accustom.

### Guzzle
There is a method `addHttpClientSubscriber` on the builder that allows you to add a subscriber to the Guzzle client.

```
$builder->addHttpClientSubscriber(new LogSubscriber());
```

### JMS Serializer
There are two methods `addSerializerSubscriber` and `addSerializerSubscribingHandler` on the builder that allows you add a subscriber or subscribing handler to the serializer.

```
$builder->addSerializerSubscriber(new MyEventSubscriber());
$builder->addSerializerSubscribingHandler(new MySubscribingHandler());
```

## Known issues
If you are importing a class in your interface that is defined in the same package as your interface, you must use the full namespaced name or declare a `use` statement even though it's not required by PHP.

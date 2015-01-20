# Retrofit PHP
This library aims to ease creation of REST clients.  It is blatantly stolen from square/retrofit (https://github.com/square/retrofit) and implemented in PHP.

## Installation
This library is still under development.  Please install from dev-master.

```
composer require tebru/retrofit-php:dev-master
```

## Setup
You must register the annotation namespaces.  For example:

```
AnnotationRegistry::registerAutoloadNamespace('Tebru\Retrofit\Annotation', __DIR__ . '/vendor/tebru/retrofit-php/src');
AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer\Annotation', __DIR__ . '/vendor/jms/serializer/src');
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
public function listRepos($user)
{
```

The are mapped automatically to parameters on the method.  Anything that maps to PHP variables can be overriden by passing a `var` key.

```
/**
 * @GET("/users/{user}/list", var="id")
 */
public function listRepos($id)
{
```

Which will allow you to name your parameter `id` in your method definition.

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
public function listRepos($user, $sort)
{
```

You can also pass in an array of parameters with `@QueryMap`, which also maps to a method parameter.

```
/**
 * @GET("/users/{user}/list")
 * @QueryMap("queryParams")
 */
public function listRepos($user, array $queryParams)
{
```

### Request Body
Request body also maps to a method parameter.  Acceptable values are `string`, `array`, or an object that can be serialied.

```
/**
 * @GET("/users/{user}/list")
 * @Body("body")
 */
public function listRepos($user, $body)
{
```

If an array is passed in, the http client will determine the correct method for sending the body (`application/x-www-form-urlencoded` or `multipart/form-data`)

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
public function listRepos($user, $accept)
{
```

### Returning
Use `@Returns` to specify a return type.  The default is `array`.  Other acceptable values are `raw` or any type specified in the JMS Serializer documentation.  A `raw` return will return the API response as a string.

```
/**
 * @GET("/users/{user}/list")
 * @Returns("ArrayCollection<My\Foo\ReposList>")
 */
public function listRepos($user, $accept)
{
```

## Sending data on every request
If there is a dynamic query parameter or header you'd like to send on each request, use a `RequestInterceptor`

```
$requestInterceptor = new \Tebru\Retrofit\RequestInterceptor();
$requestInterceptor->addQuery('authToken', $authToken);
$requestInterceptor->addHeader('My-Header', $myHeader);

...

$builder->setRequestInterceptor($requestInterceptor);
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

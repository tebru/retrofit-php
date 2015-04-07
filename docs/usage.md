Usage
=====

Annotations determine how the REST client will function

### Request Method
Each method must have a request method defined.  `GET`, `POST`, `PUT`, `DELETE`,
`HEAD`, `PATCH`, and `OPTIONS` come out of the box.

The value, if provided, represents the path that will be appended to the base url.

### Override Base Url
Occasionally an API will specify the exact url to make a request.  This can be handled
using the `@Url` annotation.

```php
/**
 * @GET()
 * @Url("url")
 */
public function listRepos($url);
```

If the `@Url` annotation is specified, the request will be made to that exact url.  Any
value passed to `@GET` will be discarded.

### URL Manipulation
As seen before, parameters can be defined by inserting curly braces into the url
path.

```php
/**
 * @GET("/users/{user}/list")
 */
public function listRepos($user);
```

The are mapped automatically to parameters on the method.

### Query Parameters
Query parameters can be added to the url

```php
@GET("/users/{user}/list?sort=desc")
```

Or as an annotation which maps to a method parameter and lets you change the 
value at runtime using `@Query`.

```php
/**
 * @GET("/users/{user}/list")
 * @Query("sort")
 */
public function listRepos($user, $sort);
```

Any annotation that maps to PHP variables can be overriden by passing a `var` key.

```php
/**
 * @GET("/users/{user}/list")
 * @Query("sort", var="foo")
 */
public function listRepos($user, $foo);
```

You can also pass in an array of parameters with `@QueryMap`, which also maps to
a method parameter.

```php
/**
 * @GET("/users/{user}/list")
 * @QueryMap("queryParams")
 */
public function listRepos($user, array $queryParams);
```

Passing `['foo' => 'bar']` to $queryParams will result in a query formatted like
`?foo=bar` while passing `['key' => ['foo' => 'bar']]` will result in `?key[foo]=bar`.


### Request Body
Request body also maps to a method parameter.  Acceptable values are `string`, 
`array`, or an object that can be serialied.

```php
/**
 * @GET("/users/{user}/list")
 * @Body("body")
 */
public function listRepos($user, $body);
```

If an array is passed in, the http client will determine the correct method for
sending the body (`application/x-www-form-urlencoded` or `multipart/form-data`)

You can also build the body dynamically with `@Part` annotations. 
Each annotation maps to a method parameter and will create a body array.

```php
/**
 * @GET("/users/{user}/list")
 * @Part("part1")
 * @Part("part2", var="foo")
 */
public function listRepos($user, $part1, $foo);
```

Both `@Body` and `@Part` annotations cannot be set.

The body can be sent as json by adding the `@JsonBody` annotation

```php
/**
 * @GET("/users/{user}/list")
 * @Body("body")
 * @JsonBody
 */
public function listRepos($user, $body);
```

### Headers
Headers can be set on the class or method using `@Headers`.  If they're set on the class, they'll be applied to each method.

```php
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

```php
/**
 * @GET("/users/{user}/list")
 * @Header("Accept", var="accept")
 */
public function listRepos($user, $accept);
```


### Returning
Use `@Returns` to specify a return type.  The default is `array`.  Other acceptable values are `raw` or any type specified in the JMS Serializer documentation.  A `raw` return will return the API response as a string.

```php
/**
 * @GET("/users/{user}/list")
 * @Returns("ArrayCollection<My\Foo\ReposList>")
 */
public function listRepos($user, $accept);
```


Events
------

Add event listeners directly to the guzzle client or serializer, or add a 
subscriber to the builder.


### Examples of handling request events

```php
$httpClient->getEmiter()->on('before', function use ($myHeader) (BeforeEvent $event) {
    $request = $event->getRequest();
    
    $request->getQuery()->add('sort', 'desc');
    $request->addHeader('My-Header', $myHeader);
});
```

```php
$httpCient->getEmiter()->on('complete', function (CompleteEvent $event) {
    $responseBody = (string)$event->getResponse()->getBody();
    $responseBody = json_decode($responseBody);
    
    if ('success' !== $responseBody['status'], true) {
        throw new Exception('boo!');
    }
});
```


Interoperability
----------------

The two main technologies backing Retrofit are Guzzle and JMS Serializer.  We aim to provide enough integration points with these libraries so you can use them as you're accustom.


### Guzzle
There is a method `addHttpClientSubscriber` on the builder that allows you to add a subscriber to the Guzzle client.

```php
$builder->addHttpClientSubscriber(new LogSubscriber());
```


### JMS Serializer
There are two methods `addSerializerSubscriber` and `addSerializerSubscribingHandler` on the builder that allows you add a subscriber or subscribing handler to the serializer.

```php
$builder->addSerializerSubscriber(new MyEventSubscriber());
$builder->addSerializerSubscribingHandler(new MySubscribingHandler());
```

Command
-------

Use the included command to generate the cache files

```
vendor/bin/retrofit <path/to/src/dir> <path/to/cache/dir>
```

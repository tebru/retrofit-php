Usage
=====

Annotations determine how the REST client will function.  Some annotations are allowed to be placed on both
the class level and method level.  Additionally, annotations will be parsed on parent interfaces.

Inheritance is ordered this way:

1. Method
2. Method's class
3. Interfaces' parent method of the same name
4. Interfaces' class

If an annotation is found on a lower priority level, it will be skipped if it already exists, even if there can be
annotations of that type.  We only inherit annotations that do not currently exist.

Building a RestAdapter
----------------------

The easiest way to create a RestAdapter is to use the builder.  It can be
as simple as

    RestAdapter::builder()
        ->setBaseUrl('http://api.example.com')
        ->build();
        
You can use a custom http client by passing in the appropriate adapter

    RestAdapter::builder()
        ->setBaseUrl('http://api.example.com')
        ->setClientAdapter($httpClientAdapter)
        ->build();

If you need a custom JMS serializer, you can pass that in

    RestAdapter::builder()
        ->setBaseUrl('http://api.example.com')
        ->setSerializer($serializer)
        ->build();

You can add an event subscriber

    RestAdapter::builder()
        ->setBaseUrl('http://api.example.com')
        ->addSubscriber($eventSubscriber)
        ->build();

A log subscriber will always be added, which will log after a request is
made and if an api exception occurs.  It will only log if a logger is passed
in.

    RestAdapter::builder()
        ->setBaseUrl('http://api.example.com')
        ->setLogger($logger)
        ->build();

If you don't like the way the default logging is formatted, you can use your own

    RestAdapter::builder()
        ->setBaseUrl('http://api.example.com')
        ->addSubscriber($logSubscriber)
        ->setLogger($logger)
        ->ignoreLogSubscriber()
        ->build();
        

Request Method
--------------

Each method must have a request method defined.  `GET`, `POST`, `PUT`, `DELETE`,
`HEAD`, `PATCH`, and `OPTIONS` come out of the box.

The value, if provided, represents the path that will be appended to the base url.

Override Base Url
-----------------

Occasionally an API will specify the exact url to make a request.  This can be handled
using the `@BaseUrl` annotation.

    /**
     * @GET()
     * @BaseUrl("url")
     */
    public function listRepos($url);

If the `@BaseUrl` annotation is specified, the request will be made to that exact url.

URL Manipulation
----------------

As seen before, parameters can be defined by inserting curly braces into the url
path.

    /**
     * @GET("/users/{user}/list")
     */
    public function listRepos($user);

The are mapped automatically to parameters on the method.

Query Parameters
----------------

Query parameters can be added to the url

    @GET("/users/{user}/list?sort=desc")

Or as an annotation which maps to a method parameter and lets you change the 
value at runtime using `@Query`.

    /**
     * @GET("/users/{user}/list")
     * @Query("sort")
     */
    public function listRepos($user, $sort);

Any annotation that maps to PHP variables can be overriden by passing a `var` key.

    /**
     * @GET("/users/{user}/list")
     * @Query("sort", var="foo")
     */
    public function listRepos($user, $foo);

You can also pass in an array of parameters with `@QueryMap`, which also maps to
a method parameter.

    /**
     * @GET("/users/{user}/list")
     * @QueryMap("queryParams")
     */
    public function listRepos($user, array $queryParams);

Passing `['foo' => 'bar']` to $queryParams will result in a query formatted like
`?foo=bar` while passing `['key' => ['foo' => 'bar']]` will result in `?key[foo]=bar`.


Request Body
------------

Request body also maps to a method parameter.  Acceptable values are `string`, 
`array`, or an object that can be serialized.

    /**
     * @GET("/users/{user}/list")
     * @Body("body")
     */
    public function listRepos($user, $body);

If an array is passed in, the http client will determine the correct method for
sending the body (`application/x-www-form-urlencoded` or `multipart/form-data`)

You can also build the body dynamically with `@Part` annotations. 
Each annotation maps to a method parameter and will create a body array.

    /**
     * @GET("/users/{user}/list")
     * @Part("part1")
     * @Part("part2", var="foo")
     */
    public function listRepos($user, $part1, $foo);

Both `@Body` and `@Part` annotations cannot be set.

The body can be sent as json by adding the `@JsonBody` annotation.

    /**
     * @GET("/users/{user}/list")
     * @Body("body")
     * @JsonBody
     */
    public function listRepos($user, $body);

The body can be sent as multipart by using the `@Multipart` annotation.

    /**
     * @GET("/users/{user}/list")
     * @Body("body")
     * @Multipart
     */
    public function listRepos($user, $body);

Headers
-------

Headers can be set on the class or method using `@Headers`.  If they're set on the class, they'll be applied to each method.

    /**
     * @Headers("Accept: application/vnd.github.v3.full+json")
     * @Headers({
     *   "Cache-Control: private, max-age=0, no-cache",
     *   "User-Agent: Retrofit-Sample-App"
     * })
     */
    interface GitHubService
    {

They can also be set individually on methods with `@Header` and map to a method parameter.

    /**
     * @GET("/users/{user}/list")
     * @Header("Accept", var="accept")
     */
    public function listRepos($user, $accept);


Returning
---------

Use `@Returns` to specify a return type.  The default is `array`.  Other
acceptable values are `raw` or any type specified in the JMS Serializer
documentation.  A `raw` return will return the API response as a string.

    /**
     * @GET("/users/{user}/list")
     * @Returns("ArrayCollection<My\Foo\ReposList>")
     */
    public function listRepos($user);

Additionally, `Response` can be a value in `@Returns`.  This annotation
requires `@ResponseType` as well.  The value in `@ResponseType` must be
a value that can be passed into `@Returns`

    /**
     * @GET("/users/{user}/list")
     * @Returns("Response")
     * @ResponseType("array")
     */
    public function listRepos($user);

This will return a PSR-7 compliant response that will contain an additional
method `body()`.  Calling this method will return the body in the format
specified with the `@ResponseType` annotation.

Events
------

Add event listeners directly to the Guzzle client or serializer, or add an event
dispatcher to the builder.


### Examples of handling request events with Guzzle 5

    $httpClient->getEmitter()->on('before', function use ($myHeader) (BeforeEvent $event) {
        $request = $event->getRequest();
        
        $request->getQuery()->add('sort', 'desc');
        $request->addHeader('My-Header', $myHeader);
    });

    $httpCient->getEmitter()->on('complete', function (CompleteEvent $event) {
        $responseBody = (string)$event->getResponse()->getBody();
        $responseBody = json_decode($responseBody);
        
        if ('success' !== $responseBody['status'], true) {
            throw new Exception('boo!');
        }
    });

Because Guzzle 6 does not include the same event system, a rudimentary version has been added
to Retrofit.

### BeforeSendEvent

Retrofit will dispatch a `retrofit.beforeSend` event before a request is made.  It includes:

- The request method
- The request url
- Any request headers
- A request body

### AfterSendEvent

Similarly, a `retrofit.afterSend` event will be dispatched after a request has been completed. It
only includes the response body.

### ApiExceptionEvent

If the http client throws an exception, it will be caught and a `retrofit.apiException` event will be
dispatched.  Additionally, a new `RetrofitApiException` will be thrown.


Exceptions
----------

If the http client throws an exception, a `RetrofitApiException` will be thrown.  This exception includes
original message and code of the caught exception, the original exception, and the class of the client
that the exception came from.

Interoperability
----------------

The two main technologies backing Retrofit are Guzzle and JMS Serializer.

Use the `setHttpClient` or `setSerializer` methods on the builder to use a custom configured version of either.

Retrofit supports version 5 or 6 of Guzzle.

There are two methods `setSerializationContext` and `setDeserializationContext` on the builder that allow you add
JMS serializer contexts that will be used in during serialization/deserialization.

    $builder->setSerializationContext(SerializationContext::create());
    $builder->setDeserializationContext(DeserializationContext::create());

Command
-------

Use the included command to generate the cache files.

    vendor/bin/retrofit compile <path/to/src/dir> <path/to/cache/dir>

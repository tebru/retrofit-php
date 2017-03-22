Annotation Reference
====================

`@Body`
-------
Defines the body of the HTTP request.
 
Use this annotation on a service when you want to directly control the 
request body of a request (instead of sending in as request parameters or 
form-style request body).

`@Header`
---------
Represents an HTTP header name/value pair to be attached to the request.


`@Headers`
---------
Adds headers literally supplied in the value.

```php
/**
 * @Headers("Cache-Control: max-age=640000")
 * @Headers({
 *     "X-Foo: Bar",
 *     "X-Ping: Pong"
 * })
 */
```

`@JsonBody`
-----------
Indicates that the body of the request should be serialized into JSON.

`@Multipart`
-----------
Indicates that the body of the request should be sent as `multipart/form-data`

`@Part`
-------
Denotes a single part of a multi-part request.

This annotation defines a method parameter that will be added as a part of 
the request. If the variable name differs from the desired part name, you 
may specify a different variable name using the `var=` parameter on this
annotation. 
You can define multiple of these annotations for multiple variable parts.

```php
/**
 * @Part("part1")
 * @Part("part2", var="foo")
 */
```

`@Query`
--------
Query parameter appended to the URL.

Values are converted to strings and then URL encoded.

Simple Example:

```php
/**
 * @GET("/list")
 * @Query("page")
 */
```

If the variable name differs from the desired part name, you may specify a
different variable name using the `var=` parameter on this annotation. 

```php
/**
 * @Query("page", var="inputPage")
 */
```

`@QueryMap`
-----------
Associative array of query parameters to append to the URL.

Values are converted to strings and then URL encoded.

Simple Example:

```php
/**
 * @GET("/list")
 * @QueryMap("parameters")
 */
```


`@Returns`
----------
Defines what type of object a request returns, so that it may be deserialized.
 
The default is `array`. Other acceptable values are `raw` or any type specified 
in the Serializer documentation. A raw return will return the API response as
a string.

If you need the entire response back, you can ask for a typed response.

```php
/**
 * @Returns(Response<raw>)
 * @Returns(Response<array>)
 * @Returns(Response<My\Object>)
 */
```

This will return a PSR-7 Response object with an added method `body()`.  Calling
this method will return the type of body you need, raw, array, or an object.

`@BaseUrl`
----------
Allows you to override the base url for a specific method or class.

Request Type Annotations
------------------------

Each method must have a request method defined. Annotations for 
`@GET`, `@POST`, `@PUT`, `@DELETE`, `@HEAD`, `@PATCH`, and `@OPTIONS`
come out of the box.

Each of these can be used to specify a path relative to the base URL defined
in the rest adapter.

```php
/**
 * @POST('/books')
 */
```

`@Serializer\SerializationContext`
----------------------------------

Defines the SerializationContext to use for the request.

```php
/**
 * @Serializer\SerializationContext(groups={"Default", "extra"}, serializeNull=true, enableMaxDepthChecks=true, version=1)
 */
```

Any extra values will be added as attributes to the context.

```php
/**
 * @Serializer\SerializationContext(attr="value", foo="bar")
 */
```

`@Serializer\DeserializationContext`
------------------------------------

Defines the DeserializationContext to use for the response.

```php
/**
 * @Serializer\DeserializationContext(depth=4, groups={"Default", "extra"}, serializeNull=true, enableMaxDepthChecks=true, version=1)
 */
```

Any extra values will be added as attributes to the context.

```php
/**
 * @Serializer\DeserializationContext(attr="value", foo="bar")
 */
```

Annotation Reference
====================

A simple example will be provided to show usage for each annotation,
but may not be a completely functioning snippet. All usages may not
have an example.

- [Body](#body)
- [DELETE](#delete)
- [ErrorBody](#errorbody)
- [Field](#field)
- [FieldMap](#fieldmap)
- [GET](#get)
- [HEAD](#head)
- [Header](#header)
- [HeaderMap](#headermap)
- [Headers](#headers)
- [OPTIONS](#options)
- [Part](#part)
- [PartMap](#partmap)
- [PATCH](#patch)
- [Path](#path)
- [POST](#post)
- [PUT](#put)
- [Query](#query)
- [QueryMap](#querymap)
- [QueryName](#queryname)
- [REQUEST](#request)
- [ResponseBody](#responsebody)
- [Url](#url)

Body
----

Defines a json body for the HTTP request.

This annotation sets the content type of the request to
`application/json`. By default, the parameter must be a PSR-7
`StreamInterface`, but adding a converter will allow additional
types to be provided and converted to json.

```php
/**
 * @Body("myBody")
 */
public function example(Mybody $myBody): Call;
```

DELETE
------

Performs a DELETE request to the provided path.

```php
/**
 * @DELETE("/foo?q=test")
 */
public function example(): Call;
```

ErrorBody
---------

Defines the type the response body should be converted to on errors.

Errors are defined as any http status code outside of 200-300. By
default, only `StreamInterface` is supported, but a converter
will extend the supported types.

```php
/**
 * @ErrorBody("App\MyClass")
 */
public function example(): Call;
```

Field
-----

Adds values to a form encoded body.

This annotation sets the request content type to
`application/x-www-form-urlencoded`. The value of this annotation
is both the name of the field and the name of the method parameter.
This can be overridden using the `var` key in the annotation. The
provided argument will be converted to a string before getting applied
to the request. Passing in an array will apply each value to the field
name. The `encoded` key will specify if the data is already encoded,
and therefore shouldn't be re-encoded. This value defaults to false.

```php
/**
 * @Field("field1")
 * @Field("field2", encoded=true)
 * @Field("field3[]", var="field3")
 */
public function example(int $field1, bool $field2, array $field3): Call;
```

FieldMap
--------

This works much like [@Field](#field), except it must be provided an
iterable map. Each key/value pair will be treated as a `@Field` with
the key acting as the field name. The value may be anything that
`@Field` supports.

```php
/**
 * @FieldMap("fieldMap")
 */
public function example(Traversable $fieldMap): Call;
```

GET
---

Performs a GET request to the provided path.

```php
/**
 * @GET("/foo?q=test")
 */
public function example(): Call;
```

HEAD
----

Performs a HEAD request to the provided path.

```php
/**
 * @HEAD("/foo?q=test")
 */
public function example(): Call;
```

Header
------

Add a single header to a request.

The value of this annotation represents both the name of the header
and the parameter name. Header names are lower-cased before getting
added to the request. Headers follow PSR-7 request format, so multiple
values with the same name may be added to the request. Passing an
array as the header value also allows multiple values to be added
under the same header name. Headers will converted to a string before
being applied to the request.

```php
/**
 * @Header("X-Foo", var="header1")
 * @Header("X-Foo", var="header2")
 * @Header("X-Foo", var="header3")
 */
public function example(string $header1, bool $header2, array $header3): Call;
```

The above example will add multiple different values to the `x-foo`
header.

HeaderMap
---------

Allows a way to add multiple headers to a request at once. The annotation
acts much like [FieldMap](#fieldmap), and accepts the same values as
[Header](#header).

```php
/**
 * @HeaderMap("headerMap")
 */
public function example(Traversable $headerMap): Call;
```

Headers
-------

Adds headers statically supplied in the annotation value.

```php
/**
 * @Headers({
 *     "X-Foo: Bar",
 *     "X-Ping: Pong"
 * })
 */
```

OPTIONS
-------

Performs an OPTIONS request to the provided path.

```php
/**
 * @OPTIONS("/foo?q=test")
 */
public function example(): Call;
```

Part
----

Adds values to a multipart body.

This annotation set the request content type to `multipart/form-data`.
This annotation can accept any value [@Body](#body) can or a special
`MultipartBody` class, which you can use to set additional properties
like headers or the filename. The annotation value specifies the part
name. If you're using `MultipartBody` the part name will be pulled from
the object and the annotation value will be ignored.

```php
/**
 * @Part("part1")
 * @Part("part2")
 */
public function example(MyBody $part1, MultipartBody $part2): Call;
```

PartMap
-------

This annotation works like previous map annotations. Keys represent
part names, and values may be anything that a [@Part](#part) can
accept. The same rules apply.

```php
/**
 * @PartMap("partMap")
 */
public function example(Traversable $partMap): Call;
```

PATCH
-----

Performs a PATCH request to the provided path.

```php
/**
 * @PATCH("/foo?q=test")
 */
public function example(): Call;
```

Path
----

This annotation allows you to create a dynamic url, and map path
parts to method parameters. By default, the annotation value will
be the same as the url placeholder and parameter name, but this is
overridable by using the `var` annotation key. Path values will get
converted to strings before getting added to the url.

```php
/**
 * @GET("/foo/{user}/{foo-bar}"
 * @Path("user")
 * @Path("foo-bar", var="fooBar")
 */
public function example(int $user, string $fooBar): Call;
```

POST
----

Performs a POST request to the provided path.

```php
/**
 * @POST("/foo?q=test")
 */
public function example(): Call;
```

PUT
---

Performs a PUT request to the provided path.

```php
/**
 * @PUT("/foo?q=test")
 */
public function example(): Call;
```

Query
-----

Add a query to the url.

The annotation value represents the query name. The value will be
converted to a string before being added to the url. Use the `var`
key if the parameter name doesn't match the query name and the `encoded`
key to specify if the query data is already encoded. This defaults to
false. Passing an array will apply each value to to the specified name.

```php
/**
 * @Query("query1")
 * @Query("query2", encoded=true)
 * @Query("query3[]", var="field3")
 */
public function example(int $query1, bool $query2, array $query3): Call;
```

QueryMap
--------

Adds multiple queries to a url.

This annotation works the same as other map annotations and adds
key/value pairs of queries to a url where keys represent the query name
and the value may be anything that [@Query](#query) allows.

```php
/**
 * @QueryMap("queryMap")
 */
public function example(Traversable $queryMap): Call;
```

QueryName
---------

Adds only the query name part of a query, excluding the `=` and anything
after it. The annotation value represents the name (which can be changed
with the `var` key), and you can specify that the data is already encoded
with the `encoded` key. Data is converted to a string before getting
added to the url.

```php
/**
 * @QueryName("queryName1")
 * @QueryName("queryName2", encoded=true)
 */
public function example(float $queryName1, string $queryName2): Call;
```

REQUEST
-------

Performs a custom request method to the provided path.

Additionally, the method type and whether the request contains a body
must be specified.

```php
/**
 * @REQUEST("/foo?q=test", type="FOO", body=false)
 */
public function example(): Call;
```

ResponseBody
------------

Defines the type the response body should be converted to on success.

Successful responses are defined as any http status code with 200-300.
By default, only `StreamInterface` is supported, but a converter
will extend the supported types.

```php
/**
 * @ResponseBody("App\MyClass")
 */
public function example(): Call;
```

Url
---

This annotation allows changing the base url of a request. This will
allow overriding whatever url is specified on the `RetrofitBuilder`.

```php
/**
 * @Url("baseUrl")
 */
public function example(string $baseUrl): Call;
```

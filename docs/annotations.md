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

    @Headers("Cache-Control: max-age=640000")
    @Headers({
        "X-Foo: Bar",
        "X-Ping: Pong"
    })


`@JsonBody`
-----------
Indicates that the body of the request should be serialized into JSON.


`@Part`
-------
Denotes a single part of a multi-part request.

This annotation defines a method parameter that will be added as a part of 
the request. If the variable name differs from the desired part name, you 
may specify a different variable name using the `var=` parameter on this
annotation. 
You can define multiple of these annotations for multiple variable parts.

    @Part("part1")
    @Part("part2", var="foo")


`@Query`
--------
Query parameter appended to the URL.

Values are converted to strings and then URL encoded.

Simple Example:

    @GET("/list")
    @Query("page")

If the variable name differs from the desired part name, you may specify a
different variable name using the `var=` parameter on this annotation. 

    @Query("page", var="inputPage")


`@QueryMap`
-----------
Query parameter appended to the URL.

Values are converted to strings and then URL encoded.

Simple Example:

    @GET("/list")
    @Query("page")

If the variable name differs from the desired part name, you may specify a
different variable name using the `var=` parameter on this annotation. 

    @Query("page", var="inputPage")


`@Returns`
----------
Defines what type of object a request returns, so that it may be deserialized.
 
The default is array. Other acceptable values are raw or any type specified 
in the Serializer documentation. A raw return will return the API response as
a string.


Request Type Annotations
------------------------

Each method must have a request method defined. Annotations for 
`@GET`, `@POST`, `@PUT`, `@DELETE`, `@HEAD`, `@PATCH`, and `@OPTIONS`
come out of the box.

Each of these can be used to specify a path relative to the base URL defined
in the rest adapter.

    @POST('/books')

`@Serializer\SerializationContext`
----------------------------------

Defines the SerializationContext to use for the request.

	@Serializer\SerializationContext(groups={"Default", "extra"}, serializeNull=true, version=1)

Any extra values will be added as attributes to the context.

	@Serializer\SerializationContext(attr="value", foo="bar")

Using this annotation will create it's own context and not use the context provided to the builder.
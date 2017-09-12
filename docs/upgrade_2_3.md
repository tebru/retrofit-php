Upgrading from Retrofit 2 to Retrofit 3
=======================================

Retrofit 3 is a complete rewrite of Retrofit 2 in an attempt to make
the library operate more closely with the Square version. This includes
changing how annotations operate. Therefore, creating a friendly upgrade
path would have been extremely time consuming, and not that useful, as
nearly everything has been deprecated.

- [Overview](#overview)
- [Feature Updates](#feature-updates)
- [Dependency Updates](#dependency-updates)
- [File Updates](#file-updates)
- [Annotation Updates](#annotation-updates)

Overview
--------

The way that requests are sent and responses are handled has changed
dramatically. Walking through how the steps have changed is the best
way to illustrate these differences.

### Retrofit 2

1. Collect all services class names
2. Read annotations and generate PHP code based on annotations
3. Cache code on filesystem
4. Use RestAdapterBuilder to build a RestAdapter instance
5. Use RestAdapter to load the service class from the filesystem and return instance
6. Call methods on the service to make http requests
7. Return expected response based on annotations

### Retrofit 3

1. Use RetrofitBuilder to build a Retrofit instance
2. Use Retrofit to ask for a Proxy object based on interface class name
3. Generate proxy object, which is an implementation of the provided interface
4. Call methods on proxy object, which internally reads annotations and
returns a Call object
5. Use the call object to execute a request synchronously or asynchronously,
and returns a Response object
6. Fetch deserialized response body from Response object based on success/failure
of request

The new method has several advantages.

- Nothing is written to the filesystem unless caching is enabled. This
fixes some issues with interfaces changing in development.
- All requests can be made asynchronously. Previously a Callback needed
to be defined in the method signature to enable async requests.
- There is more flexibility in getting different deserialized response
bodies depending on the success or failure of the request.
- The code is testable, as the only generated code is tiny and the same
for every method as it just delegate to a real class. Previously testing
what got generated was painful and prone to error.

In addition, the generated class namespace has changed from
`Tebru\Retrofit\Generated` to `Tebru\Retrofit\Proxy`.

Feature Updates
---------------

The following are high level feature changes

- The event system has been removed
- All methods require a return type (this must be `Call` if not using a custom adapter)
- All method parameter require a typehint
- All types are supported for all annotations mapped to a parameter. This
is handled through the converter system.
- Custom annotations may be used to modify the request

Dependency Updates
------------------

### Upgraded

- php: from 5.4 to 7.1
- nikic/php-parer: from ~1.3 to ~3.0.6

### Added

- symfony/cache (switched from doctrine/cache)
- tebru/doctrine-annotation-reader
- tebru/php-type

### Removed

- jms/serializer
- phpoption/phpption
- tebru/assert
- tebru/dynamo
- tebru/retrofit-http-clients
- symfony/event-dispatcher
- symfony/filesystem
- psr/log
- doctrine/cache
- doctrine/collections

Additionally removed the coveralls binary that was used for pushing
code coverage to Coveralls. This unfortunately added Guzzle 3 classes,
which could be confusing if the binary wasn't ignored.

File Updates
------------

There are many new files, but the directory structure has changed. There
is now a an `Internal` package that represents classes that should only
be used internally by the library. They may change between releases,
but will not represent a BC break. All classes will be referenced through
an interface or abstract class with few exceptions.

### Removed Directories

The following directories and all files have been removed.

- src/Adapter
- src/Generation
- src/Event
- src/Exception
- src/Subscriber

### Removed Files

The following files have been removed.

- src/Http/AsyncAware.php
- src/Http/Callback.php
- src/Http/Response.php

Annotation Updates
------------------

Many annotations have changed behavior. Additionally, annotations have
been added, removed, and renamed.

### Changed Annotations

- @Body now only works with application/json requests
- @Body may not appear with requests that do not have a body
- @Body removed jsonSerializable option, use a custom converter instead
- @GET, @HEAD, @OPTIONS, @DELETE may no longer be used with an request that contains a body
- @Part now specifically denotes a multipart request
- @Part now allows specification of encoding type (defaults to binary)
- @Part must accept a body value that can be converted, or the new MultipartBody
- @PartMap is an iterable where keys are names as strings and values are the same as @Part
- @Query added ability to specify whether data is pre-encoded or not
- Passing an array to a @Query parameter will now create separate
parameters with the name as the query name and the value as one of the array
- @QueryMap is now an iterable with supported values the same as @Query

### Added Annotations

- @Field: Used only for form encoded request bodies. An array will add all values to the field name
- @FieldMap, @HeaderMap: Like corresponding annotation, but for any iterable where string keys are the name
- @Path is new and required to map url placeholders to parameters
- @QueryName allows adding a query parameter that doesn’t have a value
- @REQUEST is a generic http request annotation like @GET or @POST

### Removed Annotations

- @FormUrlEncoded - use @Field/@FieldMap instead
- @JsonBody - use @Body instead
- @Multipart - use @Part/@PartMap instead
- @ResponseType – this was specifically created to handle cases where a response instead of body was returned and is no longer needed
- @SerializationContext/@DeserializationContext - jms/serializer was removed from project

### Renamed Annotations

- @BaseUrl to @Url
- @Returns to @ResponseBody


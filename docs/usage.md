Usage
=====

To start, it would be a good idea to get an understanding of what
Retrofit is doing under the hood.

Let's assume we're using the following service definition

```php
interface GitHubService
{
    /**
     * @GET("/users/{user}/list")
     * @Path("user")
     * @Query("limit")
     */
    public function listRepos(string $user, int $limit): Call;
}
```

First, you need an instance of `Retrofit`. Here is the easiest way to
create one.

```php
$retrofit = Retrofit::builder()
    ->setBaseUrl('http://api.example.com')
    ->setHttpClient(new Guzzle6HttpClient(new Client()))
    ->build();
```

Creating a `GitHubService` implementation is easy

```php
$githubService = $retrofit->create(GitHubService::class);
```

This is going to generate a `Proxy` object that implements the
`GitHubService` interface. You can use the proxy like you normally would
use an instance of `GitHubService`.

```php
$call = $githubService->listRepos('octocat', 10);
```

This will parse the `GitHubService` interface, and store the following
information about the `listRepos` method:

1. It should make a `GET` request to `/users/{user}/list`
2. `{user}` is provided by the `$user` parameter, which is a string
3. `limit` is a query parameter and provided by `$limit`, which is an integer
3. We're returning a `Call` object
4. We provided two arguments and they're `octocat` and `10`

This information is available in the `Call` object, so when you're ready
to make the request, it has enough information to construct the request.

Doing so can be done synchronously or asynchronously. Here's an example
of a synchronous request

```php
$response = $call->execute();
```

And asynchronously

```php
$call->enqueue(
    function(Response $response) {
        // handle any response (200, 404, 500)
    },
    function(Throwable $throwable) {
        // handle an exception (network unreachable)
    }
);
$call->wait();
```

Both of the callbacks are optional. If you just wanted to make the
request and didn't care about the response, you could do this instead

```php
$call->enqueue();
$call->wait();
```

Calling `->wait()` is necessary to trigger sending the requests. Using
the guzzle client, the requests are sent in batches of 5 using a Pool.
This allows callbacks to trigger as soon as one response is received.

`Response` here is a Retrofit Response (`Tebru\Retrofit\Response`). It
provides an easy way to check if the request was successful (if the
status code is between 200 and 300) and methods for getting the success
or error response bodies.

```php
if ($response->isSuccessful()) {
    // success body
    $response->body(); // StreamInterface
} else {
    // error body
    $response->errorBody(); // StreamInterface
}
```

By default, Retrofit only works with a PSR-7 `StreamInterface` for
setting request bodies and handling response bodies. This is somewhat
limiting, but can be enhanced by using converters. The converter I'm
going to demonstrate uses [Gson](https://github.com/tebru/gson-php)
because it can handle the conversion from any type to json and back.

Adding it to the builder is simple

```php
RetrofitBuilder::builder()
    // ...
    ->addConverterFactory(new GsonConverterFactory(Gson::builder()->build()))
    // ...
```

And allows using a lot more annotations of the client. Here's an example
using the `ResponseBody` and `ErrorBody` annotations to inform Retrofit
how to convert success and error responses

```php
interface GitHubService
{
    /**
     * @GET("/users/{user}/list")
     * @Path("user")
     * @Query("limit")
     * @ResponseBody("App\GithubService\ListRepo")
     * @ErrorBody("App\GitHubService\ApiError")
     */
    public function listRepos(string $user, int $limit): Call;
}
```

The annotation value is the class name that the response should be
deserialized into. This allows for cleaner handling of responses

```php
if (!$response->isSuccessful()) {
    // throw an exception with the deserialized body to be handled elsewhere
    throw new ApiException($response->errorBody());
}

$listRepos = $response->body();
foreach ($listRepos as $repo) {
    // iterate over the repo list
}
```

Converters can also be used when sending json. For example, assume this
service definition

```php
/**
 * @ErrorBody("App\GitHubService\ApiError")
 */
interface GitHubService
{
    /**
     * @POST("/user/repos")
     * @Body("repo")
     * @ResponseBody("App\GitHubService\Repo")
     */
    public function createRepo(Repo $repo): Call;
}
```

Because we have already registered a converter that can handle any
object, Retrofit will make a POST request to `/user/repos`, convert the
`Repo` object to json, add a `Content-Type` header of `application/json`,
and deserialize the response into a `App\GitHubService\Repo` object.

We've discussed `RequestBodyConverter`s and `ResponseBodyConverters`s.
The third type is a `StringConverter`. Adding query parameters to the
request was introduced earlier and uses a string converter to convert
various types to strings.

As seen before, we were able to easily convert an integer to a string.
The same works for a string, float, and boolean types. Booleans get
converted to `"true"` or `"false"`.

However, using an array as a query parameter has a unique effect as it
will apply each item in the array to the same query key. Let's look at
an example.

```php
interface GitHubService
{
    /**
     * @GET("/user/list")
     * @Query("affiliation[]", var="affiliations")
     */
    public function listRepos(array $affiliations): Call;
}

$githubService->listRepos(['owner', 'collaborator']);
```

*The actual GitHub API passes affiliations as a comma separated string,
but for the purposes of this example, we'll pretend it's in array
syntax.*

Here we're using the `var` key to tell Retrofit to not look for a
parameter called `$affiliation[]`—as that's illegal—but `$affiliations`
instead. This will create the query string

```
affiliation[]=owner&affiliation[]=collaborator
```

The `[]` at the end is not magic, Retrofit would behave the same way if
it was missing. It just tells servers that this data is an array.

Multiple `@Query` annotations may be used on the same method

```php
interface GitHubService
{
    /**
     * @GET("/user/list")
     * @Query("affiliation[]", var="affiliations")
     * @Query("sort")
     */
    public function listRepos(array $affiliations, string $sort): Call;
}
```

This can tend to get lengthy, which is where `@QueryMap` becomes useful.
A QueryMap is just an iterable hash of string keys and values. The
values can be anything that can be passed to a regular `@Query`. Here's
an example using an array

```php
interface GitHubService
{
    /**
     * @GET("/user/list")
     * @QueryMap("queries")
     */
    public function listRepos(array $queries): Call;
}

$githubService->listRepos([
    'affiliations[]' => ['owner', 'collaborator'],
    'sort' => 'created',
]);
```

But an object that implements `Iterable` could also be used

```php
interface GitHubService
{
    /**
     * @GET("/user/list")
     * @QueryMap("queries")
     */
    public function listRepos(ListReposQueries $queries): Call;
}

$githubService->listRepos(new ListReposQueries());
```

Parameters (and all parameters) are optional and accept default values.
Specifying default null here will not send any query parameters.

```php
interface GitHubService
{
    /**
     * @GET("/user/list")
     * @QueryMap("queries")
     */
    public function listRepos(?ListReposQueries $queries = null): Call;
}

$githubService->listRepos();
```

This behavior is consistent with `@Field`/`@FieldMap` and
`@Header`/`@HeaderMap`.

Please see the [Annotation Reference](annotations.md) for a more in-depth
look at the different annotations and how they function.

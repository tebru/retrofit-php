Retrofit PHP
============

[![Build Status](https://travis-ci.org/tebru/retrofit-php.svg?branch=master)](https://travis-ci.org/tebru/retrofit-php)

This library aims to ease creation of REST clients.  It is blatantly stolen from
[square/retrofit][retrofit]  and implemented in PHP.


Overview
--------

Retrofit allows you to define your REST API with a simple interface.

```php
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

Annotations are used to configure the endpoint.
Then, the `RestAdapter` class generates a working implementation of the 
service interface.

```php
<?php

use Tebru\Retrofit\Adapter\RestAdapter;

$restAdapter = RestAdapter::builder()
    ->setBaseUrl('https://api.github.com')
    ->build();
    
$gitHubService = $restAdapter->create(GitHubService::class);
```

Our newly created service is capable of making GET requests to /users/$user/list
to return an ArrayCollection of ListRepo objects.

```php
$repos = $gitHubService->listRepos('octocat');
```

*Usage examples are referenced from Square's documentation*


Installation & Usage
--------------------

    composer require tebru/retrofit-php:~1.0


### Documentation 

- [Detailed Installation]
- [Usage]
- [Annotation Reference]


License
-------

This project is licensed under the MIT license. Please see the `LICENSE` file
for more information.


[retrofit]: https://github.com/square/retrofit
[detailed installation]: docs/installation.md
[usage]: docs/usage.md
[annotation reference]: docs/annotations.md

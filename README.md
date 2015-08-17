Retrofit PHP
============

[![Build Status](https://travis-ci.org/tebru/retrofit-php.svg?branch=master)](https://travis-ci.org/tebru/retrofit-php)
[![Coverage Status](https://coveralls.io/repos/tebru/retrofit-php/badge.svg?branch=master)](https://coveralls.io/r/tebru/retrofit-php?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tebru/retrofit-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tebru/retrofit-php/?branch=master)

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

#### Version 2.x

- [v2 Detailed Installation]
- [v2 Usage]
- [v2 Annotation Reference]

#### Version 1.x

- [v1 Detailed Installation]
- [v1 Usage]
- [v1 Annotation Reference]

License
-------

This project is licensed under the MIT license. Please see the `LICENSE` file
for more information.


[retrofit]: https://github.com/square/retrofit

[v1 detailed installation]: docs/v1/installation.md
[v1 usage]: docs/v1/usage.md
[v1 annotation reference]: docs/v1/annotations.md

[v2 detailed installation]: docs/v2/installation.md
[v2 usage]: docs/v2/usage.md
[v2 annotation reference]: docs/v2/annotations.md

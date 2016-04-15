<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Service;

use Tebru\Retrofit\Annotation as Rest;

/**
 * Interface MockServiceBaseUrl
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MockServiceBaseUrl
{
    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\BaseUrl("baseUrl")
     * @Rest\Header("foo")
     * @Rest\Query("test")
     */
    public function baseUrl($baseUrl, $foo, $test);
}

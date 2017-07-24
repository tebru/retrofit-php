<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\ServiceMethod\ServiceMethodFactoryTest;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\ErrorBody;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\ResponseBody;
use Tebru\Retrofit\Call;

/**
 * Interface ServiceMethodFactoryTestClient
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ServiceMethodFactoryTestClient
{
    /**
     * @GET("/foo")
     */
    public function foo(): Call;

    /**
     * @GET("/bar")
     */
    public function bar();

    /**
     * @GET("/")
     * @Body("body")
     */
    public function baz(Body $body): Call;

    /**
     * @GET("/")
     * @ResponseBody("Foo")
     * @ErrorBody("Bar")
     */
    public function qux(): Call;
}

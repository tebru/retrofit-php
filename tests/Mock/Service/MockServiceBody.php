<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Service;

use Tebru\Retrofit\Annotation as Rest;
use Tebru\Retrofit\Test\Mock\MockUser;

/**
 * Interface MockServiceBody
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MockServiceBody
{
    /**
     * @Rest\POST("/post")
     * @Rest\Body("myBody")
     */
    public function simpleBody(array $myBody);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("myBody", var="foo")
     */
    public function bodyChangeName(array $foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user")
     */
    public function objectBody(MockUser $user);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user", var="foo")
     */
    public function objectBodyChangeName(MockUser $foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo")
     * @Rest\Part("bar")
     */
    public function parts($foo, $bar);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo", var="bar")
     * @Rest\Part("bar", var="foo")
     */
    public function partsChangeName($bar, $foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("myBody")
     * @Rest\JsonBody
     */
    public function jsonBody(array $myBody);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user")
     * @Rest\JsonBody
     * @return
     */
    public function objectJsonBody(MockUser $user);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo")
     * @Rest\Part("bar")
     * @Rest\JsonBody
     */
    public function partsJsonBody($foo, $bar);


    /**
     * @Rest\POST("/post")
     * @Rest\Header("foo")
     * @Rest\Body("bar")
     * @Rest\JsonBody()
     */
    public function headerJsonBody($foo, array $bar);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("bar")
     * @Rest\Multipart()
     */
    public function multipart(array $bar);
}

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
     * @Rest\FormUrlEncoded()
     */
    public function simpleBody(array $myBody);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("myBody", var="foo")
     * @Rest\FormUrlEncoded()
     */
    public function bodyChangeName(array $foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user")
     * @Rest\JsonBody()
     */
    public function objectBody(MockUser $user);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user", var="foo")
     * @Rest\JsonBody()
     */
    public function objectBodyChangeName(MockUser $foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user")
     * @Rest\FormUrlEncoded()
     */
    public function objectBodyAsFromEncoded(MockUser $user);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user")
     * @Rest\FormUrlEncoded()
     */
    public function objectBodyOptional(MockUser $user = null);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("user", jsonSerializable=true)
     * @Rest\FormUrlEncoded()
     */
    public function objectBodyJsonSerializable(MockUser $user);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo")
     * @Rest\Part("bar")
     * @Rest\FormUrlEncoded()
     */
    public function parts($foo, $bar);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo", var="bar")
     * @Rest\Part("bar", var="foo")
     * @Rest\FormUrlEncoded()
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

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo")
     * @Rest\Multipart()
     */
    public function multipartWithParts($foo);
}

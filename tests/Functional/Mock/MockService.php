<?php

namespace Tebru\Retrofit\Test\Functional\Mock;

use Tebru\Retrofit\Annotation as Rest;
use Tebru\Retrofit\Test\Functional\Mock\MockUser;

interface MockService
{
    /**
     * @Rest\GET("/get");
     */
    public function simpleGet();

    /**
     * @Rest\POST("/post");
     */
    public function simplePost();

    /**
     * @Rest\PUT("/put");
     */
    public function simplePut();

    /**
     * @Rest\DELETE("/delete");
     */
    public function simpleDelete();

    /**
     * @Rest\HEAD("/head");
     */
    public function simpleHead();

    /**
     * @Rest\OPTIONS("/options");
     */
    public function simpleOptions();

    /**
     * @Rest\PATCH("/patch");
     */
    public function simplePatch();

    /**
     * @Rest\GET("/get/{id}")
     */
    public function getWithVar($id);

    /**
     * @Rest\GET("/get?foo=bar")
     */
    public function getWithQuery();

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     */
    public function getWithQueryDynamic($baz);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz", var="buzz")
     */
    public function canChangeQueryVar($buzz);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     * @Rest\QueryMap("map")
     */
    public function getWithQueryMap($baz, array $map);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     * @Rest\QueryMap("map")
     */
    public function getWithQueryMapNested($baz, array $map);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     * @Rest\QueryMap("map", var="mapped")
     */
    public function canChangeQueryMapVar($baz, array $mapped);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("body")
     */
    public function postWithSimpleBody($body);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("body", var="foo")
     */
    public function canChangeBodyVar($foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Body("body")
     */
    public function postWithObjectBody(MockUser $body);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo")
     */
    public function postWithPart($foo);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo")
     * @Rest\Part("baz")
     */
    public function postWithMultipleParts($foo, $baz);

    /**
     * @Rest\POST("/post")
     * @Rest\Part("foo", var="bar")
     */
    public function canChangePartVar($bar);

    /**
     * @Rest\GET("/get")
     * @Rest\Header("foo")
     */
    public function getWithHeader($foo);

    /**
     * @Rest\GET("/get")
     * @Rest\Header("foo", var="bar")
     */
    public function canChangeHeaderVar($bar);

    /**
     * @Rest\GET("/get")
     * @Rest\Header("foo")
     * @Rest\Header("baz")
     */
    public function getWithMultipleHeaders($foo, $baz);

    /**
     * @Rest\GET("/get")
     * @Rest\Headers("Foo: bar")
     */
    public function getWithStaticHeaders();

    /**
     * @Rest\GET("/get")
     * @Rest\Headers({
     *     "Foo: bar",
     *     "Baz: buzz"
     * })
     */
    public function getWithStaticHeadersList();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("raw")
     */
    public function getRawReturn();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("array")
     */
    public function getArrayReturn();

    /**
     * @Rest\GET("/get")
     */
    public function getDefaultReturnIsArray();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("Tebru\Retrofit\Test\Functional\Mock\MockUser")
     */
    public function getDeserializedReturn();
}

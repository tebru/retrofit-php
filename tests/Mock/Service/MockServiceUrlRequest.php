<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Service;

use Tebru\Retrofit\Annotation as Rest;

/**
 * Interface MockServiceUrlRequest
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MockServiceUrlRequest
{
    const MY_CONST = 1;

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
    public function urlParam($id);

    /**
     * @Rest\GET("/get?foo=bar")
     */
    public function urlQuery();

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     */
    public function variableQuery($baz);

    /**
     * @Rest\GET("/get")
     * @Rest\Query("foo")
     */
    public function variableQueryWithArray($foo);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz", var="buzz")
     */
    public function variableQueryChangeName($buzz);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     * @Rest\QueryMap("map")
     */
    public function queryMap($baz, array $map);

    /**
     * @Rest\GET("/get?foo=bar")
     * @Rest\Query("baz")
     * @Rest\QueryMap("map", var="mapped")
     */
    public function queryMapChangeName($baz, array $mapped);

    /**
     * @Rest\GET("/get");
     * @Rest\Query("foo")
     * @Rest\Query("bar")
     * @Rest\Query("baz")
     * @Rest\Query("kit")
     * @Rest\Query("kat")
     */
    public function defaultParams($foo = null, $bar = 1, $baz = '', $kit = true, $kat = self::MY_CONST);
}

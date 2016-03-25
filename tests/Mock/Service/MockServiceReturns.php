<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Service;

use Tebru\Retrofit\Annotation as Rest;

/**
 * Interface MockServiceReturns
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Rest\Serializer\DeserializationContext(depth=4, enableMaxDepthChecks=true, serializeNull=true)
 */
interface MockServiceReturns
{
    /**
     * @Rest\GET("/get")
     * @Rest\Returns("raw")
     */
    public function rawReturn();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("array")
     */
    public function arrayReturn();

    /**
     * @Rest\GET("/get")
     * @Rest\Serializer\DeserializationContext(depth=3, enableMaxDepthChecks=true, serializeNull=true)
     * @Rest\Returns("Tebru\Retrofit\Test\Mock\MockUser")
     */
    public function deserializedReturn();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("Response<array>")
     */
    public function responseReturnArray();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("Response<raw>")
     */
    public function responseReturnRaw();

    /**
     * @Rest\GET("/get")
     * @Rest\Returns("Response<Tebru\Retrofit\Test\Mock\MockUser>")
     */
    public function responseReturnObject();
}

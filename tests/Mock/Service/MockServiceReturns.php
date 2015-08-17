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
     * @Rest\Returns("Tebru\Retrofit\Test\Mock\MockUser")
     */
    public function deserializedReturn();
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock;

use Tebru\Retrofit\Annotation as Rest;

/**
 * Interface MockSimpleService
 *
 * @author Nate Brunette <nbrunett@nerdery.com>
 */
interface MockSimpleService
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
}

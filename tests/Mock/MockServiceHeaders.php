<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock;

use Tebru\Retrofit\Annotation as Rest;

/**
 * Interface MockServiceHeaders
 *
 * @author Nate Brunette <nbrunett@nerdery.com>
 *
 * @Rest\Headers({
 *     "foo: bar",
 *     "baz: buzz"
 * })
 */
interface MockServiceHeaders
{
    /**
     * @Rest\GET("/get");
     */
    public function noHeaders();

    /**
     * @Rest\GET("/get");
     * @Rest\Header("kit")
     */
    public function oneHeader($kit);

    /**
     * @Rest\GET("/get");
     * @Rest\Header("foo")
     * @Rest\Header("kit")
     */
    public function headerOverwrite($foo, $kit);
}

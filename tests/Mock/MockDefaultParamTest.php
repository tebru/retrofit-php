<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock;

use Tebru\Retrofit\Annotation as Rest;

/**
 * Interface MockDefaultParamTest
 *
 * @author Nate Brunette <nbrunett@nerdery.com>
 */
interface MockDefaultParamTest
{
    const MY_CONST = 1;

    /**
     * @Rest\GET("/get");
     * @Rest\Query("foo")
     * @Rest\Query("bar")
     * @Rest\Query("baz")
     * @Rest\Query("buzz")
     * @Rest\Query("kit")
     * @Rest\Query("kat")
     */
    public function defaultParams($foo = null, $bar = 1, $baz = '', $buzz = [], $kit = true, $kat = self::MY_CONST);
}

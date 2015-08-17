<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Service;

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
    public function noMethodHeaders();

    /**
     * @Rest\GET("/get");
     * @Rest\Header("kit")
     */
    public function oneMethodHeader($kit);

    /**
     * @Rest\GET("/get");
     * @Rest\Header("kit", var="kat")
     */
    public function headerChangeName($kat);

    /**
     * @Rest\GET("/get");
     * @Rest\Header("foo")
     * @Rest\Header("kit")
     */
    public function headerOverwrite($foo, $kit);
}

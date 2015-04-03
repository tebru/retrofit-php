<?php
/**
 * File MockServiceHeaders.php
 */

namespace Tebru\Retrofit\Test\Functional\Mock;

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

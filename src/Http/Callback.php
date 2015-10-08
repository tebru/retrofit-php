<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Http;

/**
 * Interface Callback
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
interface Callback
{
    /**
     * @return callable
     */
    public function success();

    /**
     * @return callable
     */
    public function failure();
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Http;

/**
 * Interface AsyncAware
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
interface AsyncAware
{
    /**
     * @return callable
     */
    public function wait();
}

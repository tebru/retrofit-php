<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\ServiceResolver\OneInterface;

use Tebru\Retrofit\Annotation\GET;

/**
 * Interface OneFoo
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface OneFoo
{
    /**
     * @GET("")
     */
    public function get();
}

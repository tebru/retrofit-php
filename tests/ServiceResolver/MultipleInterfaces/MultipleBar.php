<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\ServiceResolver\MultipleInterfaces;

use Tebru\Retrofit\Annotation\GET;

/**
 * Interface MultipleBar
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MultipleBar
{
    /**
     * @GET()
     */
    public function get();
}

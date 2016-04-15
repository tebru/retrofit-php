<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\ServiceResolver\MultipleInterfaces;

use Tebru\Retrofit\Annotation\GET;

/**
 * Interface OneFoo
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface MultipleFoo
{
    /**
     * @GET("")
     */
    public function get();
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Builder\Factory;

use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;

/**
 * Class MethodBodyBuilderFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MethodBodyBuilderFactory
{
    /**
     * Create a new method body builder
     *
     * @return MethodBodyBuilder
     */
    public function make()
    {
        return new MethodBodyBuilder();
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Tebru\Retrofit\Internal\RequestBuilder;

/**
 * Interface ParameterHandler
 *
 * Implementors of this interface will be able to handle different method parameters
 * provided during runtime.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ParameterHandler
{
    /**
     * Set a value to the [@see RequestBuilder] for parameter type
     *
     * @param RequestBuilder $requestBuilder
     * @param mixed $value
     * @return void
     */
    public function apply(RequestBuilder $requestBuilder, $value): void;
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\CallAdapter;

use Tebru\Retrofit\Call;
use Tebru\Retrofit\CallAdapter;

/**
 * Class DefaultCallAdapter
 *
 * By default, do not alter a [@see Call]
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultCallAdapter implements CallAdapter
{
    /**
     * Accepts a [@see Call] and converts it to the appropriate type
     *
     * @param Call $call
     * @return Call
     */
    public function adapt(Call $call): Call
    {
        return $call;
    }
}

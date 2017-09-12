<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

/**
 * Interface CallAdapter
 *
 * Implementors can modify a [@see Call] to match the expected service method
 * return type.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface CallAdapter
{
    /**
     * Accepts a [@see Call] and converts it to the appropriate type
     *
     * @param Call $call
     * @return mixed
     */
    public function adapt(Call $call);
}

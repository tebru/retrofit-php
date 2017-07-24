<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\CallAdapter;
use Tebru\Retrofit\CallAdapterFactory;

/**
 * Class RetrofitTestCallAdapterFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTestCallAdapterFactory implements CallAdapterFactory
{
    /**
     * Returns true if the factory supports this type
     *
     * @param TypeToken $type
     * @return bool
     */
    public function supports(TypeToken $type): bool
    {
        return $type->isA(RetrofitTestAdaptedCallMock::class);
    }

    /**
     * Create a new factory from type
     *
     * @param TypeToken $type
     * @return CallAdapter
     */
    public function create(TypeToken $type): CallAdapter
    {
        return new RetrofitTestCallAdapterMock();
    }
}

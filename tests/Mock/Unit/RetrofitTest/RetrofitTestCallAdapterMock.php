<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\Retrofit\Call;
use Tebru\Retrofit\CallAdapter;

/**
 * Class RetrofitTestCallAdapterMock
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTestCallAdapterMock implements CallAdapter
{
    /**
     * Accepts a [@see Call] and converts it to the appropriate type
     *
     * @param Call $call
     * @return mixed
     */
    public function adapt(Call $call)
    {
        return new RetrofitTestAdaptedCallMock();
    }
}

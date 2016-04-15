<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Exception;

use Tebru\Retrofit\Exception\RetrofitApiException;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RetrofitApiExceptionTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitApiExceptionTest extends MockeryTestCase
{
    public function testCanCreate()
    {
        $exception = new RetrofitApiException(get_class($this));
        $this->assertInstanceOf(RetrofitApiException::class, $exception);
    }

    public function testGetClientClass()
    {
        $exception = new RetrofitApiException(get_class($this));
        $this->assertSame(self::class, $exception->getClientClass());
    }
}

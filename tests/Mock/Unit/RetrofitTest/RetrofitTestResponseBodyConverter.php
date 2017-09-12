<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Psr\Http\Message\StreamInterface;
use Tebru\Retrofit\ResponseBodyConverter;

/**
 * Class RetrofitTestResponseBodyConverter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTestResponseBodyConverter implements ResponseBodyConverter
{
    /**
     * Convert from stream to any type
     *
     * @param StreamInterface $value
     * @return mixed
     */
    public function convert(StreamInterface $value)
    {
        return new RetrofitTestResponseBodyMock();
    }
}

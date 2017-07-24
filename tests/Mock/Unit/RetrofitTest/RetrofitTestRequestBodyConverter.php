<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use function GuzzleHttp\Psr7\stream_for;
use Psr\Http\Message\StreamInterface;
use Tebru\Retrofit\RequestBodyConverter;

/**
 * Class RetrofitTestRequestBodyConverter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTestRequestBodyConverter implements RequestBodyConverter
{
    /**
     * Convert to stream
     *
     * @param RetrofitTestRequestBodyMock $value
     * @return StreamInterface
     */
    public function convert($value): StreamInterface
    {
        return stream_for(json_encode(['id' => $value->id, 'name' => $value->name]));
    }
}

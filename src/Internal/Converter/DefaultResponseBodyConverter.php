<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\Converter;

use Psr\Http\Message\StreamInterface;
use Tebru\Retrofit\ResponseBodyConverter;

/**
 * Class DefaultResponseBodyConverter
 *
 * Noop, returns response body stream
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultResponseBodyConverter implements ResponseBodyConverter
{
    /**
     * By default, returns the stream
     *
     * @param StreamInterface $value
     * @return StreamInterface
     */
    public function convert(StreamInterface $value): StreamInterface
    {
        return $value;
    }
}

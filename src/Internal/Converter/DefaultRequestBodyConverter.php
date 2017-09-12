<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\Converter;

use Psr\Http\Message\StreamInterface;
use Tebru\Retrofit\RequestBodyConverter;

/**
 * Class DefaultRequestBodyConverter
 *
 * Noop, defaults to returning stream
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultRequestBodyConverter implements RequestBodyConverter
{
    /**
     * The value here should already be a stream, so we can return it
     *
     * @param mixed $value
     * @return StreamInterface
     */
    public function convert($value): StreamInterface
    {
        return $value;
    }
}

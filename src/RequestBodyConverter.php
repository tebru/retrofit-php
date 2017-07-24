<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Psr\Http\Message\StreamInterface;

/**
 * Interface RequestBodyConverter
 *
 * Convert various values to [@see StreamInterface] to be used as
 * as request body
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface RequestBodyConverter extends Converter
{
    /**
     * Convert to stream
     *
     * @param mixed $value
     * @return StreamInterface
     */
    public function convert($value): StreamInterface;
}

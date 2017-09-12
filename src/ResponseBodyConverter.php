<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Psr\Http\Message\StreamInterface;

/**
 * Interface ResponseBodyConverter
 *
 * Convert various values to [@see StreamInterface] to be used as
 * as response body
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ResponseBodyConverter extends Converter
{
    /**
     * Convert from stream to any type
     *
     * @param StreamInterface $value
     * @return mixed
     */
    public function convert(StreamInterface $value);
}

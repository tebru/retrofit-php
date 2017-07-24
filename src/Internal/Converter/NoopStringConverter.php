<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\Converter;

use Tebru\Retrofit\StringConverter;

/**
 * Class NoopStringConverter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class NoopStringConverter implements StringConverter
{
    /**
     * Only types that are known to be strings should be passed to this converter,
     * so we can just return the value.
     *
     * @param string $value
     * @return string
     */
    public function convert($value): string
    {
        return $value;
    }
}

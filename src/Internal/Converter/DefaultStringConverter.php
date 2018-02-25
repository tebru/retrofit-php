<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\Converter;

use Tebru\Retrofit\StringConverter;

/**
 * Class DefaultStringConverter
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultStringConverter implements StringConverter
{
    /**
     * Convert any supported value to a string
     *
     * @param mixed $value
     * @return string
     */
    public function convert($value): string
    {
        // if it's an array or object, just serialize it
        if (\is_array($value) || \is_object($value)) {
            return serialize($value);
        }

        if ($value === true) {
            return 'true';
        }

        if ($value === false) {
            return 'false';
        }

        return (string)$value;
    }
}

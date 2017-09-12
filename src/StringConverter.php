<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

/**
 * Interface StringConverter
 *
 * Converts a value to a string
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface StringConverter extends Converter
{
    /**
     * Convert any supported value to a string
     *
     * @param mixed $value
     * @return string
     */
    public function convert($value): string;
}

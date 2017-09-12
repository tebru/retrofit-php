<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

/**
 * Adds a field to form encoded requests
 *
 * The default value represents the field name. Passing an array will add a mapping between the
 * field name and each value in the array. The array must be 0-indexed.
 *
 * Set 'encoded' to true to specify that the data is already encoded.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Field extends Encodable
{
    /**
     * Whether or not multiple annotations of this type can
     * be added to a method
     *
     * @return bool
     */
    public function allowMultiple(): bool
    {
        return true;
    }
}

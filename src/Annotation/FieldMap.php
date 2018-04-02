<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

/**
 * Represents a collection of @Field annotations
 *
 * The default value specifies the variable name in the method signature.
 *
 * Any iterable may be passed as an argument. Keys of the map will be the field
 * names, and the values will be handled exactly the same as as @Field.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class FieldMap extends Encodable
{
    /**
     * Returns true if multiple annotations of this type are allowed
     *
     * @return bool
     */
    public function allowMultiple(): bool
    {
        return true;
    }
}

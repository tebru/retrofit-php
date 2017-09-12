<?php

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\StringConverter;

/**
 * Represents a collection of @Header annotations
 *
 * The default value specifies the variable name in the method signature.
 *
 * Any iterable may be passed as an argument. Keys of the map will be the header
 * names, and the values will be handled exactly the same as as @Header.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class HeaderMap extends ParameterAnnotation
{
    /**
     * Return the converter interface class
     *
     * Can be one of RequestBodyConverter, ResponseBodyConverter, or StringConverter
     *
     * @return string
     */
    public function converterType(): ?string
    {
        return StringConverter::class;
    }

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

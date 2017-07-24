<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\StringConverter;

/**
 * Adds a header to request
 *
 * The default value represents the header name. Passing an array will add a new value for each
 * header name.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Header extends ParameterAnnotation
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
}

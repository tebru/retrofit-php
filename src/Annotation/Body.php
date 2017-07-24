<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\RequestBodyConverter;

/**
 * Define the body of the HTTP request.
 *
 * This annotation may only be used on requests that are sending json. The parameter
 * this annotation maps to must be able to be converted to json.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Body extends ParameterAnnotation
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
        return RequestBodyConverter::class;
    }
}

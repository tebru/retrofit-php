<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\StringConverter;

/**
 * Specifies replaceable parts in a url path
 *
 * Given this service method
 *
 * /**
 *  * @GET("/foo/{my-path}")
 *  * @Path("my-path", var="myPath")
 *  *
 *  public function foo(string $myPath): Call;
 *
 * Passing in "bar" will result in the path "/foo/bar". The 'var' key is unnecessary if
 * the path value inside the {} matches the variable name.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Path extends ParameterAnnotation
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

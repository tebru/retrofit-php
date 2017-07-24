<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

/**
 * Interface ParameterAwareAnnotation
 *
 * Annotations that implement this interface are mapped to method parameters, so they
 * need to know a variable name to reference and how to convert the value.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ParameterAwareAnnotation
{
    /**
     * The variable name, which will either be the default value or the value of 'var' if
     * specified. The variable name excludes the '$'.
     *
     * @return string
     */
    public function getVariableName(): string;

    /**
     * Return the converter interface class
     *
     * Can be one of RequestBodyConverter, ResponseBodyConverter, or StringConverter
     *
     * @return null|string
     */
    public function converterType(): ?string;
}

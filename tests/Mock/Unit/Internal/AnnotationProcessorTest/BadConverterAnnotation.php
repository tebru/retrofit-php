<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\AnnotationProcessorTest;

use Tebru\AnnotationReader\AbstractAnnotation;
use Tebru\Retrofit\Annotation\ParameterAwareAnnotation;

/**
 * Class AnnotationProcessorTestAnnotation
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class BadConverterAnnotation extends AbstractAnnotation implements ParameterAwareAnnotation
{
    /**
     * The variable name, which will either be the default value or the value of 'var' if
     * specified. The variable name excludes the '$'.
     *
     * @return string
     */
    public function getVariableName(): string
    {
        return $this->getValue();
    }

    /**
     * Return the converter interface class
     *
     * Can be one of RequestBodyConverter, ResponseBodyConverter, or StringConverter
     *
     * @return null|string
     */
    public function converterType(): ?string
    {
        return 'Foo';
    }
}

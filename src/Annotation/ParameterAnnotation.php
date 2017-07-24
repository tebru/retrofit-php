<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru;
use Tebru\AnnotationReader\AbstractAnnotation;

/**
 * Parent class for annotation that have a [parameterName => $parameterName] format
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class ParameterAnnotation extends AbstractAnnotation implements ParameterAwareAnnotation
{
    /**
     * An alias for the variable name
     *
     * @var string $var
     */
    private $var;

    /**
     * Initialize annotation data
     */
    protected function init(): void
    {
        parent::init();

        $this->var = $this->data['var'] ?? null;
    }

    /**
     * Get the variable name
     *
     * @return string
     */
    public function getVariableName(): string
    {
        return $this->var ?? $this->getValue();
    }
}

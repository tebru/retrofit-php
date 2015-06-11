<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Exception;
use Tebru;
use Tebru\Retrofit\Exception\AnnotationConditionMissingException;

/**
 * Class AnnotationToVariableMap
 *
 * Parent class for annotation that have a [parameterName => $parameterName] format
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class AnnotationToVariableMap
{
    /**
     * Variable name prefixed with '$'
     *
     * @var string $value
     */
    private $value;

    /**
     * An alias for the variable name
     *
     * @var string $var
     */
    private $var;

    /**
     * Constructor
     *
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        Tebru\assert(isset($params['value']), new AnnotationConditionMissingException(sprintf('An argument was not passed to a "%s" annotation.', get_class($this))));

        $this->value = $params['value'];

        if (isset($params['var'])) {
            $this->var = $params['var'];
        }
    }

    /**
     * Get the annotation key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->value;
    }

    /**
     * Get the variable name with '$'
     *
     * @return string|array
     */
    public function getValue()
    {
        return '$' . $this->getName();
    }

    /**
     * Get the variable name
     *
     * @return string
     */
    public function getName()
    {
        return (null !== $this->var) ? $this->var : $this->value;
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Exception;
use Tebru;

/**
 * Class VariableMapper
 *
 * Parent class for annotation that have a [parameterName => $parameterName] format
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class VariableMapper
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
        Tebru\assertArrayKeyExists('value', $params, 'An argument was not passed to a "%s" annotation.', get_class($this));

        $this->value = $params['value'];

        if (array_key_exists('var', $params)) {
            $this->var = $params['var'];
        }
    }

    /**
     * Get the annotation key
     *
     * @return string
     */
    public function getRequestKey()
    {
        return $this->value;
    }

    /**
     * Get the variable name with '$'
     *
     * @return string
     */
    public function getVariable()
    {
        return '$' . $this->getVariableName();
    }

    /**
     * Get the variable name
     *
     * @return string
     */
    public function getVariableName()
    {
        return (null !== $this->var) ? $this->var : $this->value;
    }
}

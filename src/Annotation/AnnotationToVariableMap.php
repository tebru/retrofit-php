<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use OutOfRangeException;
use Tebru;

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
     * Annotation key mapped to variable
     *
     * @var string $key
     */
    private $key;

    /**
     * Variable name prefixed with '$'
     *
     * @var string $value
     */
    private $value;

    /**
     * Name of variable without '$'
     *
     * @var string
     */
    private $name;

    /**
     * Constructor
     *
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        Tebru\assert(isset($params['value']), new OutOfRangeException(sprintf('A "%s" annotation must have an argument.  None found.', get_class($this))));

        // will prepend '$' to either the original value or the 'var' key, if set
        $name = (isset($params['var']) ? $params['var'] : $params['value']);
        $value = '$' . $name;

        $this->key = $params['value'];
        $this->value = $value;
        $this->name = $name;
    }

    /**
     * Get the annotation key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the variable name with '$'
     *
     * @return string|array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the variable name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}

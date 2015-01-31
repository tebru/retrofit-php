<?php
/**
 * File AnnotationToVariableMap.php 
 */

namespace Tebru\Retrofit\Annotation;

use Exception;

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
     * Constructor
     *
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        if (!isset($params['value'])) {
            throw new Exception('Method parameter name not set on annotation');
        }

        // will prepend '$' to either the original value or the 'var' key, if set
        $value = '$' . (isset($params['var']) ? $params['var'] : $params['value']);

        $this->key = $params['value'];
        $this->value = $value;
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
     * Get the variable name
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

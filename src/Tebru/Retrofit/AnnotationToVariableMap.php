<?php
/**
 * File AnnotationToVariableMap.php 
 */

namespace Tebru\Retrofit;

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

        $this->setValue($params['value'], $value);
    }

    /**
     * Sets the key/value pair to the annotation
     *
     * @param string $key
     * @param string $value
     */
    abstract protected function setValue($key, $value);
}

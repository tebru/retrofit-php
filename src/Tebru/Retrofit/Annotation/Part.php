<?php
/**
 * File Part.php 
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\AnnotationToVariableMap;

/**
 * Class Part
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class Part extends AnnotationToVariableMap
{
    /**
     * @var string $key
     */
    private $key;

    /**
     * @var string $part
     */
    private $part;

    /**
     * Sets the key/value pair to the annotation
     *
     * @param string $key
     * @param string $value
     */
    protected function setValue($key, $value)
    {
        $this->key = $key;
        $this->part = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getPart()
    {
        return $this->part;
    }
}

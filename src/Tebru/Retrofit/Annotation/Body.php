<?php
/**
 * File Body.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\AnnotationToVariableMap;

/**
 * Class Body
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class Body extends AnnotationToVariableMap
{
    /**
     * @var mixed $body
     */
    private $body;

    /**
     * @param string $key
     * @param string $value
     */
    protected function setValue($key, $value)
    {
        $this->body = $value;
    }

    /**
     * Gets the body content
     *
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }
}

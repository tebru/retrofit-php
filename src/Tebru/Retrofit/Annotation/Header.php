<?php
/**
 * File Header.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\AnnotationToVariableMap;

/**
 * Class Header
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class Header extends AnnotationToVariableMap
{
    /**
     * @var array $header
     */
    private $header = [];

    /**
     * @param string $key
     * @param string $value
     */
    protected function setValue($key, $value)
    {
        $this->header[$key] = $value;
    }

    /**
     * @return array
     */
    public function getHeader()
    {
        return $this->header;
    }

}

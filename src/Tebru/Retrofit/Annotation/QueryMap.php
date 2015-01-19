<?php
/**
 * File QueryMap.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\AnnotationToVariableMap;

/**
 * Class QueryMap
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class QueryMap extends AnnotationToVariableMap
{
    /**
     * @var string $queryMap
     */
    private $queryMap;

    /**
     * @param string $key
     * @param string $value
     */
    protected function setValue($key, $value)
    {
        $this->queryMap = $value;
    }

    /**
     * @return string
     */
    public function getQueryMap()
    {
        return $this->queryMap;
    }
}

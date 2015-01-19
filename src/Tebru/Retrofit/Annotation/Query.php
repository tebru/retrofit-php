<?php
/**
 * File Query.php
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Retrofit\AnnotationToVariableMap;

/**
 * Class Query
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 */
class Query extends AnnotationToVariableMap
{
    /**
     * @var array $query
     */
    private $query = [];

    /**
     * @param string $key
     * @param string $value
     */
    protected function setValue($key, $value)
    {
        $this->query[$key] = $value;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }
}

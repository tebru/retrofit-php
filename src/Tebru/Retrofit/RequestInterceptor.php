<?php
/**
 * File RequestInterceptor.php 
 */

namespace Tebru\Retrofit;

/**
 * Class RequestInterceptor
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestInterceptor
{
    /**
     * Queries to add to request
     *
     * @var array $queries
     */
    private $queries = [];

    /**
     * Headers to add to request
     *
     * @var array $headers
     */
    private $headers = [];

    /**
     * Add a query parameter for the format ?name=value
     *
     * @param string $name
     * @param string $value
     */
    public function addQuery($name, $value)
    {
        $this->queries[$name] = $value;
    }

    /**
     * Get Queries
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * Add a header for the format 'Name: value'
     *
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}

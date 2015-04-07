<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use OutOfRangeException;
use Tebru;

/**
 * Parent class for Http request annotations.
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class HttpRequest
{
    /**
     * Url path
     *
     * @var string $path
     */
    private $path = '';

    /**
     * Method parameters in url
     *
     * @var array $parameters
     */
    private $parameters = [];

    /**
     * Query parameters in url
     *
     * @var array $queries
     */
    private $queries = [];

    /**
     * Constructor
     *
     * @param array $params
     * @throws OutOfRangeException if path is not set
     */
    public function __construct(array $params)
    {
        $path = $params['value'];

        // check if url contains {}
        $matchesFound = preg_match_all('/{(.+?)}/', $path, $pathMatches);
        if ($matchesFound) {
            foreach ($pathMatches[0] as $key => $match) {
                $paramName = $pathMatches[1][$key];
                $this->parameters[] = $paramName;

                // replace {variable} with $variable in path for each match found
                $path = str_replace($pathMatches[0][$key], '$' . $paramName, $path);
            }
        }

        // if url has query parameters, remove them
        $queryString = strstr($path, '?');
        if (false !== $queryString) {
            // remove ? and everything after
            $path = substr($path, 0, -strlen($queryString));

            // set $queryString to everything after the ?
            $queryString = substr($queryString, 1);

            // convert string to array and set to $stringAsArray
            parse_str($queryString, $stringAsArray);

            $this->queries = $stringAsArray;
        }

        $this->path = $path;
    }

    /**
     * Returns the type of the annotation (get, post, put, etc)
     *
     * @return string
     */
    abstract public function getType();

    /**
     * Get the url path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the method parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Get the query parameters
     *
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }
}

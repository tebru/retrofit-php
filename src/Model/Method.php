<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Model;

/**
 * Class Method
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Method
{
    /**
     * The method name
     *
     * @var string
     */
    private $name = '';

    /**
     * The method full declaration as a string
     *
     * @var string
     */
    private $declaration = '';

    /**
     * The http request method
     *
     * @var string
     */
    private $type = null;

    /**
     * A url override
     *
     * @var string
     */
    private $url = null;

    /**
     * Url path for endpoint
     *
     * @var string
     */
    private $path = '';

    /**
     * Return data type
     *
     * @var string
     */
    private $return = 'array';

    /**
     * Guzzle options
     *
     * @var array
     */
    private $options = [];

    /**
     * Body parts
     *
     * Multiple parts will be joined to create one body
     *
     * @var array
     */
    private $parts = [];

    /**
     * Request query parameters
     *
     * @var array
     */
    private $queries = [];

    /**
     * Request headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * If we are sending the body as json
     *
     * @var bool
     */
    private $jsonBody = false;

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'methodDeclaration' => $this->getDeclaration(),
            'type' => $this->getType(),
            'url' => $this->getUrl(),
            'path' => $this->getPath(),
            'return' => $this->getReturn(),
            'options' => $this->getOptions(),
            'parts' => $this->getParts(),
            'query' => $this->getQueries(),
            'headers' => $this->getHeaders(),
            'jsonBody' => $this->isJsonBody(),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDeclaration()
    {
        return $this->declaration;
    }

    /**
     * @param string $declaration
     */
    public function setDeclaration($declaration)
    {
        $this->declaration = $declaration;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param string $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * @param array $parts
     */
    public function addParts(array $parts)
    {
        $this->parts = array_merge($parts, $this->parts);
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @param array $queries
     */
    public function addQueries(array $queries)
    {
        $this->queries = array_merge($queries, $this->queries);
    }

    /**
     * @param string $queryMap
     */
    public function addQueryMap($queryMap)
    {
        $this->queries[] = $queryMap;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($headers, $this->headers);
    }

    /**
     * @return boolean
     */
    public function isJsonBody()
    {
        return $this->jsonBody;
    }

    /**
     * @param boolean $jsonBody
     */
    public function setJsonBody($jsonBody)
    {
        $this->jsonBody = $jsonBody;
    }
}

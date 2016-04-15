<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Api;

use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;

/**
 * Class MockApiResponse
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @AccessType(type="public_method")
 */
class MockApiResponse
{
    /**
     * @var string
     *
     * @Type("string")
     */
    private $method;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $path;

    /**
     * @var array
     *
     * @Type("array")
     * @SerializedName("query_params")
     */
    private $queryParams = [];

    /**
     * @var array
     *
     * @Type("array")
     */
    private $headers = [];

    /**
     * @var array
     *
     * @Type("array")
     */
    private $content = [];

    /**
     * @var array
     *
     * @Type("array")
     */
    private $files = [];

    /**
     * @var array
     *
     * @Type("array")
     */
    private $user = [];

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
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
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param array $queryParams
     * @return $this
     */
    public function setQueryParams(array $queryParams)
    {
        $this->queryParams = $queryParams;

        return $this;
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
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param array $content
     * @return $this
     */
    public function setContent(array $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param array $files
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $user
     * @return $this
     */
    public function setUser(array $user)
    {
        $this->user = $user;

        return $this;
    }
}

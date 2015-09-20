<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class BeforeSendEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BeforeSendEvent extends Event
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $requestUrl;

    /**
     * @var string
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * Constructor
     *
     * @param string $method
     * @param string $requestUrl
     * @param string $headers
     * @param string $body
     */
    public function __construct($method, $requestUrl, $headers, $body)
    {
        $this->method = $method;
        $this->requestUrl = $requestUrl;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}

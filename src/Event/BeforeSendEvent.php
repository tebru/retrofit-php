<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Psr\Http\Message\RequestInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class BeforeSendEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BeforeSendEvent extends Event
{
    /**
     * A PSR-7 Request object
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @deprecated Use getRequest()
     * @return string
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * @deprecated Use getRequest()
     * @return string
     */
    public function getRequestUrl()
    {
        return (string)$this->request->getUri();
    }

    /**
     * @deprecated Use getRequest()
     * @return array
     */
    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    /**
     * @deprecated Use getRequest()
     * @return string
     */
    public function getBody()
    {
        return (string)$this->request->getBody();
    }
}

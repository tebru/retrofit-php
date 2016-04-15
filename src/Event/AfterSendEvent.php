<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AfterSendEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AfterSendEvent extends Event
{
    const NAME = 'retrofit.afterSend';

    /**
     * The request object
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * The response object
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * Constructor
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function __construct(RequestInterface $request, ResponseInterface $response = null)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * Get the request
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the updated response back to the event
     *
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response = null)
    {
        $this->response = $response;
    }
}

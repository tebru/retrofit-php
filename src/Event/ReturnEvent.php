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
 * Class ReturnEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnEvent extends Event
{
    const NAME = 'retrofit.return';

    /**
     * What will be returned from the generated client
     *
     * @var mixed
     */
    private $return;

    /**
     * The request sent to the client
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * The response from the client
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * Constructor
     *
     * @param mixed $return
     * @param RequestInterface|null $request
     * @param ResponseInterface|null $response
     */
    public function __construct($return, RequestInterface $request = null, ResponseInterface $response = null)
    {
        $this->return = $return;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Get return
     *
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * Set updated return back to event
     *
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }

    /**
     * Get the request
     *
     * @return RequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the response
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
}

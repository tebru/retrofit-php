<?php
/*
 * Copyright (c) Nate Brunette.
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
    const NAME = 'retrofit.beforeSend';

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
     * Get the request
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the updated request back to the event
     *
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
        trigger_error(
            'Retrofit Deprecation: This method is deprecated, use getRequest() instead',
            E_USER_DEPRECATED
        );

        return $this->request->getMethod();
    }

    /**
     * @deprecated Use getRequest()
     * @return string
     */
    public function getRequestUrl()
    {
        trigger_error(
            'Retrofit Deprecation: This method is deprecated, use getRequest() instead',
            E_USER_DEPRECATED
        );

        return (string) $this->request->getUri();
    }

    /**
     * @deprecated Use getRequest()
     * @return array
     */
    public function getHeaders()
    {
        trigger_error(
            'Retrofit Deprecation: This method is deprecated, use getRequest() instead',
            E_USER_DEPRECATED
        );

        return $this->request->getHeaders();
    }

    /**
     * @deprecated Use getRequest()
     * @return string
     */
    public function getBody()
    {
        trigger_error(
            'Retrofit Deprecation: This method is deprecated, use getRequest() instead',
            E_USER_DEPRECATED
        );

        return (string) $this->request->getBody();
    }
}

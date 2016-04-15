<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Exception;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ApiExceptionEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ApiExceptionEvent extends Event
{
    const NAME = 'retrofit.apiException';

    /**
     * Any exception
     *
     * @var Exception
     */
    private $exception;

    /**
     * The request object
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Constructor
     *
     * @param Exception         $exception
     * @param RequestInterface  $request
     */
    public function __construct(Exception $exception, RequestInterface $request = null)
    {
        $this->exception = $exception;
        $this->request   = $request;
    }

    /**
     * Get the exception
     *
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Set updated exception back to event
     *
     * @param Exception $exception
     */
    public function setException(Exception $exception)
    {
        $this->exception = $exception;
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
}

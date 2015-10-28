<?php
/*
 * Copyright (c) 2015 Nate Brunette.
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
    /**
     * @var Exception
     */
    private $exception;

    /**
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
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param Exception $exception
     */
    public function setException(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}

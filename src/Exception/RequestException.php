<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Exception;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class RequestException
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestException extends Exception
{
    /**
     * PSR-7 request
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * PSR-7 response
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * Additional request context
     *
     * @var array
     */
    private $handlerContext;

    /**
     * Constructor
     *
     * @param string $message
     * @param int $code
     * @param RequestInterface $request
     * @param Exception|null $previous
     * @param ResponseInterface|null $response
     * @param array $handlerContext
     */
    public function __construct(
        $message,
        $code,
        Exception $previous = null,
        RequestInterface $request = null,
        ResponseInterface $response = null,
        array $handlerContext = []
    ) {
        parent::__construct($message, $code, $previous);

        $this->request = $request;
        $this->response = $response;
        $this->handlerContext = $handlerContext;
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
     * If a response exists
     *
     * @return bool
     */
    public function hasResponse()
    {
        return $this->response !== null;
    }

    /**
     * Get the context
     *
     * @return array
     */
    public function getHandlerContext()
    {
        return $this->handlerContext;
    }
}

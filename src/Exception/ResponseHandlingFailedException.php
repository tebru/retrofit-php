<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

/**
 * Class ResponseConversionFailedException
 *
 * This exception is thrown if there's an issue handling the response. It exists
 * in order to provide more information about the request/response to the consumer in
 * the event of a failure. It signifies that an HTTP request was successful, but could
 * not be properly handled.
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseHandlingFailedException extends RuntimeException
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        string $message = '',
        Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);

        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\HttpClientCallTest;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Tebru\Retrofit\HttpClient;

/**
 * Class HttpClientCallTestClientMock
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HttpClientCallTestClientMock implements HttpClient
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var callable
     */
    private $onResponse;

    /**
     * @var callable
     */
    private $onFailure;

    /**
     * Constructor
     *
     * @param ResponseInterface $response
     */
    public function __construct(?ResponseInterface $response = null)
    {
        $this->response = $response;
    }


    /**
     * Send a request synchronously and return a PSR-7 [@see ResponseInterface]
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Send a request asynchronously
     *
     * The response callback must be called if any response is returned from the request, and the failure
     * callback should only be executed if a request was not completed.
     *
     * The response callback should pass a PSR-7 [@see ResponseInterface] as the one and only argument. The
     * failure callback should pass a [@see Throwable] as the one and only argument.
     *
     * @param RequestInterface $request
     * @param callable $onResponse
     * @param callable $onFailure
     * @return void
     */
    public function sendAsync(RequestInterface $request, callable $onResponse, callable $onFailure): void
    {
        $this->onResponse = $onResponse;
        $this->onFailure = $onFailure;
    }

    /**
     * Calling this method should execute any enqueued requests asynchronously
     *
     * @return void
     */
    public function wait(): void
    {
        if ($this->response !== null) {
            $onResponse = $this->onResponse;
            $onResponse($this->response);
            return;
        }

        $onFailure = $this->onFailure;
        $onFailure(new RuntimeException());
    }
}

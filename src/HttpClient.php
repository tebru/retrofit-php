<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface HttpClient
 *
 * Implementors will make http requests using PSR-7 request and response objects
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface HttpClient
{
    /**
     * Send a request synchronously and return a PSR-7 [@see ResponseInterface]
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request): ResponseInterface;

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
    public function sendAsync(RequestInterface $request, callable $onResponse, callable $onFailure): void;

    /**
     * Calling this method should execute any enqueued requests asynchronously
     *
     * @return void
     */
    public function wait(): void;
}

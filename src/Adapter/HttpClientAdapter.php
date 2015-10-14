<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Http\Callback;

/**
 * Interface HttpClientAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface HttpClientAdapter
{
    /**
     * Make a request
     *
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     */
    public function send($method, $uri, array $headers = [], $body = null);

    /**
     * Make an async request
     *
     * @param RequestInterface $request
     * @param \Tebru\Retrofit\Http\Callback $callback
     * @return ResponseInterface
     */
    public function sendAsync(RequestInterface $request, Callback $callback);

    /**
     * Resolve all async requests
     *
     * @return null
     */
    public function wait();
}

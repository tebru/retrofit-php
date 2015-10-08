<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter;

use GuzzleHttp\Psr7\Request;
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
     * @param Request $request
     * @param Callback $callback
     * @return ResponseInterface
     */
    public function sendAsync(Request $request, Callback $callback);

    /**
     * Resolve all async requests
     *
     * @return null
     */
    public function wait();
}

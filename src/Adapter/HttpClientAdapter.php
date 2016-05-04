<?php
/*
 * Copyright (c) Nate Brunette.
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
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);

    /**
     * Make an async request
     *
     * @param RequestInterface $request
     * @param Callback $callback
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

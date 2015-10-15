<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Http;

use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Exception\RequestException;

/**
 * Interface Callback
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 * @author Nate Brunete <n@tebru.net>
 */
interface Callback
{
    /**
     * Called on successful responses
     *
     * @param ResponseInterface $response
     */
    public function onResponse(ResponseInterface $response);

    /**
     * Called on errors
     *
     * @param RequestException $exception
     */
    public function onFailure(RequestException $exception);
}

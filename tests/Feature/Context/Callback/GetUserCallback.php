<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Feature\Context\Callback;

use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Exception\RequestException;
use Tebru\Retrofit\Http\Callback;

/**
 * Class GetUserCallback
 *
 * @author Nate Brunette <n@tebru.net>
 */
class GetUserCallback implements Callback
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Called on successful responses
     *
     * @param ResponseInterface $response
     */
    public function onResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Called on errors
     *
     * @param RequestException $exception
     * @throws RequestException
     */
    public function onFailure(RequestException $exception)
    {
        throw $exception;
    }

    /**
     * Get the response body
     *
     * @return array
     */
    public function getResponseBody()
    {
        return json_decode((string) $this->response->getBody(), true);
    }
}

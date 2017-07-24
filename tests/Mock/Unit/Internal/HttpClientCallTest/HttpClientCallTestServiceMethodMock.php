<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\HttpClientCallTest;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Call;
use Tebru\Retrofit\Internal\ServiceMethod;

/**
 * Class HttpClientCallTestServiceMethodMock
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HttpClientCallTestServiceMethodMock implements ServiceMethod
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var HttpClientCallTestResponseBodyMock
     */
    private $response;

    /**
     * @var HttpClientCallTestErrorBodyMock
     */
    private $error;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param HttpClientCallTestResponseBodyMock $response
     * @param HttpClientCallTestErrorBodyMock $error
     */
    public function __construct(
        RequestInterface $request,
        ?HttpClientCallTestResponseBodyMock $response = null,
        ?HttpClientCallTestErrorBodyMock $error = null
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->error = $error;
    }


    /**
     * Apply runtime arguments and build request
     *
     * @param array $args
     * @return RequestInterface
     */
    public function toRequest(array $args): RequestInterface
    {
        return $this->request;
    }

    /**
     * Take a response and convert it to expected value
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function toResponseBody(ResponseInterface $response)
    {
        return $this->response;
    }

    /**
     * Take a response and convert it to expected value
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function toErrorBody(ResponseInterface $response)
    {
        return $this->error;
    }

    /**
     * Take a [@see Call] and adapt to expected value
     *
     * @param Call $call
     * @return mixed
     */
    public function adapt(Call $call)
    {
        return $call;
    }
}

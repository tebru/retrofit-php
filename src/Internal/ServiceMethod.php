<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Internal;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Call;

/**
 * Class ServiceMethod
 *
 * This is the class that [@see Call]s will interact with. Its main responsibility is taking
 * known request parameters and applying arguments supplied at runtime to build a PSR-7 request
 * object. Additionally, it converts responses and adapts [@Call]s.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ServiceMethod
{
    /**
     * Apply runtime arguments and build request
     *
     * @param array $args
     * @return RequestInterface
     */
    public function toRequest(array $args): RequestInterface;

    /**
     * Take a response and convert it to expected value
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function toResponseBody(ResponseInterface $response);

    /**
     * Take a response and convert it to expected value
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function toErrorBody(ResponseInterface $response);

    /**
     * Take a [@see Call] and adapt to expected value
     *
     * @param Call $call
     * @return mixed
     */
    public function adapt(Call $call);
}

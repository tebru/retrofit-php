<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Psr\Http\Message\RequestInterface;

/**
 * Interface Call
 *
 * Implementations will be able to make requests synchronously or asynchronously and will
 * be able to provide a PSR-7 request object.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Call
{
    /**
     * Execute request synchronously
     *
     * A [@see Response] will be returned
     *
     * @return Response
     */
    public function execute(): Response;

    /**
     * Execute request asynchronously
     *
     * This method accepts two optional callbacks.
     *
     * onResponse() will be called for any request that gets a response,
     * whether it was successful or not. It will send a [@see Call] and
     * a [@see Response] as the first and second parameters.
     *
     * onFailure() will be called in the event a network request failed. It
     * will send the [@see Throwable] that was encountered.
     *
     * Example of method signatures:
     *
     * $call->enqueue(
     *     function (\Tebru\Retrofit\Call $call, \Tebru\Retrofit\Response $response) {},
     *     function (\Throwable $throwable) {}
     * );
     *
     * @param callable $onResponse On any response
     * @param callable $onFailure On any network request failure
     * @return Call
     */
    public function enqueue(?callable $onResponse = null, ?callable $onFailure = null): Call;

    /**
     * When making requests asynchronously, call wait() to execute the requests
     *
     * @return void
     */
    public function wait(): void;

    /**
     * Get the PSR-7 request
     *
     * @return RequestInterface
     */
    public function request(): RequestInterface;
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Call;
use Tebru\Retrofit\Exception\ResponseHandlingFailedException;
use Tebru\Retrofit\HttpClient;
use Tebru\Retrofit\Response;
use Throwable;

/**
 * Class HttpClientCall
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class HttpClientCall implements Call
{
    /**
     * A retrofit http client implementation
     *
     * @var HttpClient
     */
    private $client;

    /**
     * A web service resource as a method model
     *
     * @var ServiceMethod
     */
    private $serviceMethod;

    /**
     * The runtime arguments that a request should be constructed with
     *
     * @var array
     */
    private $args;

    /**
     * The constructed request
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Constructor
     *
     * @param HttpClient $client
     * @param ServiceMethod $serviceMethod
     * @param array $args
     */
    public function __construct(HttpClient $client, ServiceMethod $serviceMethod, array $args)
    {
        $this->client = $client;
        $this->serviceMethod = $serviceMethod;
        $this->args = $args;
    }

    /**
     * Execute request synchronously
     *
     * A [@see Response] will be returned
     *
     * @return Response
     */
    public function execute(): Response
    {
        $response = $this->client->send($this->request());

        return $this->createResponse($response);
    }

    /**
     * Execute request asynchronously
     *
     * This method accepts two optional callbacks.
     *
     * onResponse() will be called for any request that gets a response,
     * whether it was successful or not. It will send a [@see Response] as
     * the parameter.
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
     * @throws \LogicException
     */
    public function enqueue(?callable $onResponse = null, ?callable $onFailure = null): Call
    {
        $this->client->sendAsync(
            $this->request(),
            function (ResponseInterface $response) use ($onResponse) {
                if ($onResponse !== null) {
                    $onResponse($this->createResponse($response));
                }
            },
            function (Throwable $throwable) use ($onFailure) {
                if ($onFailure === null) {
                    throw $throwable;
                }

                $onFailure($throwable);
            }
        );

        return $this;
    }

    /**
     * When making requests asynchronously, call wait() to execute the requests
     *
     * @return void
     */
    public function wait(): void
    {
        $this->client->wait();
    }

    /**
     * Get the PSR-7 request
     *
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
        if ($this->request === null) {
            $this->request = $this->serviceMethod->toRequest($this->args);
        }

        return $this->request;
    }

    /**
     * Create a [@see Response] from a PSR-7 response
     *
     * @param ResponseInterface $response
     * @return RetrofitResponse
     */
    private function createResponse(ResponseInterface $response): RetrofitResponse
    {
        $code = $response->getStatusCode();
        if ($code >= 200 && $code < 300) {
            try {
                $responseBody = $this->serviceMethod->toResponseBody($response);
            } catch (Throwable $throwable) {
                throw new ResponseHandlingFailedException(
                    $this->request(),
                    $response,
                    'Retrofit: Could not convert response body',
                    $throwable
                );
            }

            return new RetrofitResponse($response, $responseBody, null);
        }

        try {
            $errorBody = $this->serviceMethod->toErrorBody($response);
        } catch (Throwable $throwable) {
            throw new ResponseHandlingFailedException(
                $this->request(),
                $response,
                'Retrofit: Could not convert error body',
                $throwable
            );
        }

        return new RetrofitResponse($response, null, $errorBody);
    }
}

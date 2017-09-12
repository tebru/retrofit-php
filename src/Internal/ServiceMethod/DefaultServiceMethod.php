<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ServiceMethod;

use LogicException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Call;
use Tebru\Retrofit\CallAdapter;
use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\ParameterHandler;
use Tebru\Retrofit\ResponseBodyConverter;
use Tebru\Retrofit\Internal\ServiceMethod;

/**
 * Class DefaultServiceMethod
 *
 * This is the class that [@see Call]s will interact with. Its main responsibility is taking
 * known request parameters and applying arguments supplied at runtime to build a PSR-7 request
 * object. Additionally, it converts responses and adapts [@Call]s.
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultServiceMethod implements ServiceMethod
{
    /**
     * Request method
     *
     * @var string
     */
    private $method;

    /**
     * Request base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Request path
     *
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $headers;

    /**
     * Array of parameter handlers
     *
     * @var ParameterHandler[]
     */
    private $parameterHandlers;

    /**
     * The call adapter to use
     *
     * @var CallAdapter
     */
    private $callAdapter;

    /**
     * Response body converter
     *
     * @var ResponseBodyConverter
     */
    private $responseBodyConverter;

    /**
     * Error body converter
     *
     * @var ResponseBodyConverter
     */
    private $errorBodyConverter;

    /**
     * Constructor
     *
     * @param string $method
     * @param string $baseUrl
     * @param string $uri
     * @param array $headers
     * @param array $parameterHandlers
     * @param CallAdapter $callAdapter
     * @param ResponseBodyConverter $responseBodyConverter
     * @param ResponseBodyConverter $errorBodyConverter
     */
    public function __construct(
        string $method,
        string $baseUrl,
        string $uri,
        array $headers,
        array $parameterHandlers,
        CallAdapter $callAdapter,
        ResponseBodyConverter $responseBodyConverter,
        ResponseBodyConverter $errorBodyConverter
    ) {
        $this->method = $method;
        $this->baseUrl = $baseUrl;
        $this->path = $uri;
        $this->headers = $headers;
        $this->parameterHandlers = $parameterHandlers;
        $this->callAdapter = $callAdapter;
        $this->responseBodyConverter = $responseBodyConverter;
        $this->errorBodyConverter = $errorBodyConverter;
    }

    /**
     * Apply runtime arguments and build request
     *
     * @param array $args
     * @return RequestInterface
     * @throws \LogicException
     */
    public function toRequest(array $args): RequestInterface
    {
        if (count($this->parameterHandlers) !== count($args)) {
            throw new LogicException(sprintf(
                'Retrofit: Incompatible number of arguments. Expected %d and got %s. This either ' .
                'means that the service method was not called with the correct number of parameters, ' .
                'or there is not an annotation for every parameter.',
                count($this->parameterHandlers),
                count($args)
            ));
        }

        $requestBuilder = new RequestBuilder(
            $this->method,
            $this->baseUrl,
            $this->path,
            $this->headers
        );

        foreach ($this->parameterHandlers as $index => $parameterHandler) {
            $parameterHandler->apply($requestBuilder, $args[$index]);
        }

        return $requestBuilder->build();
    }

    /**
     * Take a response and convert it to expected value
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function toResponseBody(ResponseInterface $response)
    {
        return $this->responseBodyConverter->convert($response->getBody());
    }

    /**
     * Take a response and convert it to expected value
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    public function toErrorBody(ResponseInterface $response)
    {
        return $this->errorBodyConverter->convert($response->getBody());
    }

    /**
     * Take a [@see Call] and adapt to expected value
     *
     * @param Call $call
     * @return mixed
     */
    public function adapt(Call $call)
    {
        return $this->callAdapter->adapt($call);
    }
}

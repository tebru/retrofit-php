<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ServiceMethod;

use LogicException;
use Tebru\Retrofit\CallAdapter;
use Tebru\Retrofit\ParameterHandler;
use Tebru\Retrofit\ResponseBodyConverter;
use Tebru\Retrofit\ServiceMethodBuilder;

/**
 * Class DefaultServiceMethodBuilder
 *
 * Constructs a [@ServiceMethod]
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultServiceMethodBuilder implements ServiceMethodBuilder
{
    /**
     * The request method
     *
     * @var string
     */
    private $method;

    /**
     * The request base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * The request path
     *
     * @var string
     */
    private $path;

    /**
     * True if the request has a body
     *
     * @var bool
     */
    private $hasBody;

    /**
     * The request body content type
     *
     * @var string
     */
    private $contentType;

    /**
     * Array of headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * Array of Parameter handlers, indexed to match the position of the parameters
     *
     * @var ParameterHandler[]
     */
    private $parameterHandlers = [];

    /**
     * Converts successful response bodies to expected value
     *
     * @var ResponseBodyConverter
     */
    private $responseBodyConverter;

    /**
     * Converts error response bodies to expected value
     *
     * @var ResponseBodyConverter
     */
    private $errorBodyConverter;

    /**
     * Adapts a [@see Call] to expected value
     *
     * @var CallAdapter
     */
    private $callAdapter;

    /**
     * Set the request method (e.g. GET, POST)
     *
     * @param string $method
     * @return ServiceMethodBuilder
     * @throws \LogicException
     */
    public function setMethod(string $method): ServiceMethodBuilder
    {
        if ($this->method !== null) {
            throw new LogicException(sprintf(
                'Retrofit: Only one http method is allowed. Trying to set %s, but %s already exists',
                strtoupper($method),
                $this->method
            ));
        }

        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Set the request base url (e.g. http://example.com)
     *
     * @param string $baseUrl
     * @return ServiceMethodBuilder
     */
    public function setBaseUrl(string $baseUrl): ServiceMethodBuilder
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Set the request path
     *
     * @param string $path
     * @return ServiceMethodBuilder
     */
    public function setPath(string $path): ServiceMethodBuilder
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set to true if an annotation exists that denotes a request body. This should also set
     * the request content type.
     *
     * @param bool $hasBody
     * @return ServiceMethodBuilder
     * @throws \LogicException
     */
    public function setHasBody(bool $hasBody): ServiceMethodBuilder
    {
        if ($this->hasBody !== null && $this->hasBody !== $hasBody) {
            throw new LogicException(
                'Retrofit: Body cannot be changed after it has been set. This indicates a conflict between ' .
                'HTTP Request annotations, body annotations, and request type annotations. For example, ' .
                '@GET cannot be used with @Body, @Field, or @Part annotations'
            );
        }

        $this->hasBody = $hasBody;

        return $this;
    }

    /**
     * Set the content type of the request. A content type should not be set if there
     * isn't a request body.
     *
     * @param string $contentType
     * @return ServiceMethodBuilder
     * @throws \LogicException
     */
    public function setContentType(string $contentType): ServiceMethodBuilder
    {
        if ($this->contentType !== null && $this->contentType !== $contentType) {
            throw new LogicException(
                'Retrofit: Content type cannot be changed after it has been set. This indicates a conflict between ' .
                'HTTP Request annotations, body annotations, and request type annotations. For example, ' .
                '@GET cannot be used with @Body, @Field, or @Part annotations'
            );
        }

        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Convenience method to declare that the request has content and is json
     *
     * @return ServiceMethodBuilder
     * @throws \LogicException
     */
    public function setIsJson(): ServiceMethodBuilder
    {
        $this->setHasBody(true);
        $this->setContentType('application/json');

        return $this;
    }


    /**
     * Convenience method to declare that the request has content and is form encoded
     *
     * @return ServiceMethodBuilder
     * @throws \LogicException
     */
    public function setIsFormUrlEncoded(): ServiceMethodBuilder
    {
        $this->setHasBody(true);
        $this->setContentType('application/x-www-form-urlencoded');

        return $this;
    }

    /**
     * Convenience method to declare that the request has content and is multipart
     *
     * @return ServiceMethodBuilder
     * @throws \LogicException
     */
    public function setIsMultipart(): ServiceMethodBuilder
    {
        $this->setHasBody(true);
        $this->setContentType('multipart/form-data');

        return $this;
    }

    /**
     * Add a request header. Header name should be normalized.
     *
     * @param string $name
     * @param string $header
     * @return ServiceMethodBuilder
     */
    public function addHeader(string $name, string $header): ServiceMethodBuilder
    {
        $this->headers[strtolower($name)][] = $header;

        return $this;
    }

    /**
     * Add a [@see ParameterHandler] at the position the parameter exists
     *
     * @param int $index
     * @param ParameterHandler $parameterHandler
     * @return ServiceMethodBuilder
     */
    public function addParameterHandler(int $index, ParameterHandler $parameterHandler): ServiceMethodBuilder
    {
        $this->parameterHandlers[$index] = $parameterHandler;

        return $this;
    }

    /**
     * Set the [@see CallAdapter]
     *
     * @param CallAdapter $callAdapter
     * @return ServiceMethodBuilder
     */
    public function setCallAdapter(CallAdapter $callAdapter): ServiceMethodBuilder
    {
        $this->callAdapter = $callAdapter;

        return $this;
    }

    /**
     * Set the response body converter to convert successful responses
     *
     * @param ResponseBodyConverter $responseBodyConverter
     * @return ServiceMethodBuilder
     */
    public function setResponseBodyConverter(ResponseBodyConverter $responseBodyConverter): ServiceMethodBuilder
    {
        $this->responseBodyConverter = $responseBodyConverter;

        return $this;
    }

    /**
     * Set the response body converter to convert error responses
     *
     * @param ResponseBodyConverter $errorBodyConverter
     * @return ServiceMethodBuilder
     */
    public function setErrorBodyConverter(ResponseBodyConverter $errorBodyConverter): ServiceMethodBuilder
    {
        $this->errorBodyConverter = $errorBodyConverter;

        return $this;
    }

    /**
     * Create a new [@see DefaultServiceMethod] from previously set parameters
     *
     * @return DefaultServiceMethod
     * @throws \LogicException
     */
    public function build(): DefaultServiceMethod
    {
        if ($this->method === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method without HTTP method. Please specify @GET, @POST, etc'
            );
        }

        if ($this->baseUrl === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method without base url. Please specify on RetrofitBuilder'
            );
        }

        if ($this->path === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method without HTTP method. Please specify @GET, @POST, etc'
            );
        }

        if ($this->hasBody === true && $this->contentType === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method with body and no content type. Set one using @Body, ' .
                '@Field, or @Part'
            );
        }

        if ($this->hasBody !== true && $this->contentType !== null) {
            throw new LogicException(
                'Retrofit: Cannot set a content-type without a body. This indicates a conflict between ' .
                'HTTP Request annotations, body annotations, and request type annotations. For example, ' .
                '@GET cannot be used with @Body, @Field, or @Part annotations'
            );
        }

        if ($this->responseBodyConverter === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method without response body converter'
            );
        }

        if ($this->errorBodyConverter === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method without error body converter'
            );
        }

        if ($this->callAdapter === null) {
            throw new LogicException(
                'Retrofit: Cannot build service method without call adapter'
            );
        }

        if ($this->contentType !== null && !isset($this->headers['content-type'])) {
            $this->addHeader('content-type', $this->contentType);
        }

        if ($this->hasBody === null) {
            $this->hasBody = false;
        }

        ksort($this->parameterHandlers);

        return new DefaultServiceMethod(
            $this->method,
            $this->baseUrl,
            $this->path,
            $this->headers,
            $this->parameterHandlers,
            $this->callAdapter,
            $this->responseBodyConverter,
            $this->errorBodyConverter
        );
    }
}

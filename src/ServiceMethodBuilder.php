<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

/**
 * Class ServiceMethodBuilder
 *
 * Implementations will allow for constructing a [@see DefaultServiceMethod] iteratively
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ServiceMethodBuilder
{
    /**
     * Set the request method (e.g. GET, POST)
     *
     * @param string $method
     * @return ServiceMethodBuilder
     */
    public function setMethod(string $method): ServiceMethodBuilder;

    /**
     * Set the request base url (e.g. http://example.com)
     *
     * @param string $baseUrl
     * @return ServiceMethodBuilder
     */
    public function setBaseUrl(string $baseUrl): ServiceMethodBuilder;

    /**
     * Set the request path
     *
     * @param string $path
     * @return ServiceMethodBuilder
     */
    public function setPath(string $path): ServiceMethodBuilder;

    /**
     * Set to true if an annotation exists that denotes a request body. This should also set
     * the request content type.
     *
     * @param bool $hasBody
     * @return ServiceMethodBuilder
     */
    public function setHasBody(bool $hasBody): ServiceMethodBuilder;

    /**
     * Set the content type of the request. A content type should not be set if there
     * isn't a request body.
     *
     * @param string $contentType
     * @return ServiceMethodBuilder
     */
    public function setContentType(string $contentType): ServiceMethodBuilder;

    /**
     * Convenience method to declare that the request has content and is json
     *
     * @return ServiceMethodBuilder
     */
    public function setIsJson(): ServiceMethodBuilder;

    /**
     * Convenience method to declare that the request has content and is form encoded
     *
     * @return ServiceMethodBuilder
     */
    public function setIsFormUrlEncoded(): ServiceMethodBuilder;

    /**
     * Convenience method to declare that the request has content and is multipart
     *
     * @return ServiceMethodBuilder
     */
    public function setIsMultipart(): ServiceMethodBuilder;

    /**
     * Add a request header. Header name should be normalized.
     *
     * @param string $name
     * @param string $header
     * @return ServiceMethodBuilder
     */
    public function addHeader(string $name, string $header): ServiceMethodBuilder;

    /**
     * Add a [@see ParameterHandler] at the position the parameter exists
     *
     * @param int $index
     * @param ParameterHandler $parameterHandler
     * @return ServiceMethodBuilder
     */
    public function addParameterHandler(int $index, ParameterHandler $parameterHandler): ServiceMethodBuilder;

    /**
     * Set the [@see CallAdapter]
     *
     * @param CallAdapter $callAdapter
     * @return ServiceMethodBuilder
     */
    public function setCallAdapter(CallAdapter $callAdapter): ServiceMethodBuilder;
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Response;

/**
 * Wraps a PSR-7 [@see ResponseInterface] and provides convenience methods for getting
 * a converted success or error body.
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class RetrofitResponse implements Response
{
    /**
     * The PSR-7 response
     *
     * @var ResponseInterface
     */
    private $response;

    /**
     * Converted body on success
     *
     * @var mixed
     */
    private $body;

    /**
     * Converted body on failure
     *
     * @var mixed
     */
    private $errorBody;

    /**
     * Constructor
     *
     * @param ResponseInterface $response
     * @param mixed $body
     * @param mixed $errorBody
     */
    public function __construct(ResponseInterface $response, $body, $errorBody)
    {
        $this->response = $response;
        $this->body = $body;
        $this->errorBody = $errorBody;
    }

    /**
     * Get the raw PSR-7 response
     *
     * @return ResponseInterface
     */
    public function raw(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the response status code
     *
     * @return int
     */
    public function code(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get the response message
     *
     * @return string
     */
    public function message(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * Get response headers
     *
     * @return array
     */
    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * Returns true if the response was successful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300;
    }

    /**
     * Get converted body
     *
     * @return mixed
     */
    public function body()
    {
        return $this->body;
    }

    /**
     * Get converted body on errors
     *
     * @return mixed
     */
    public function errorBody()
    {
        return $this->errorBody;
    }
}

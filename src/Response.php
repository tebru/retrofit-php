<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface Response
 *
 * Wraps a PSR-7 [@see ResponseInterface] and provides convenience methods for getting
 * a converted success or error body.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Response
{
    /**
     * Get the raw PSR-7 response
     *
     * @return ResponseInterface
     */
    public function raw(): ResponseInterface;

    /**
     * Get the response status code
     *
     * @return int
     */
    public function code(): int;

    /**
     * Get the response message
     *
     * @return string
     */
    public function message(): string;

    /**
     * Get response headers
     *
     * @return array
     */
    public function headers(): array;

    /**
     * Returns true if the response was successful
     *
     * @return bool
     */
    public function isSuccessful(): bool;

    /**
     * Get converted body
     *
     * @return mixed
     */
    public function body();

    /**
     * Get converted body on errors
     *
     * @return mixed
     */
    public function errorBody();
}

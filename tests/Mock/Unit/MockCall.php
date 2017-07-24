<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit;

use Psr\Http\Message\RequestInterface;
use Tebru\Retrofit\Call;
use Tebru\Retrofit\Response;

/**
 * Class MockCall
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MockCall implements Call
{
    /**
     * @return Response
     */
    public function execute(): Response
    {
    }

    /**
     * @param callable $onResponse
     * @param callable $onFailure
     * @return Call
     */
    public function enqueue(?callable $onResponse = null, ?callable $onFailure = null): Call
    {
    }

    /**
     * @return void
     */
    public function wait(): void
    {
    }

    /**
     * @return RequestInterface
     */
    public function request(): RequestInterface
    {
    }
}

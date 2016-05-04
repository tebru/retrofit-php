<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Exception;

use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use Tebru\Retrofit\Exception\RequestException;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RequestExceptionTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestExceptionTest extends MockeryTestCase
{
    public function testGetters()
    {
        $previousException = new Exception();
        $request = new Request('GET', '/get');
        $response = new Response(200);
        $exception = new RequestException('test', 100, $previousException, $request, $response, ['test' => 'test']);

        $this->assertSame('test', $exception->getMessage());
        $this->assertSame(100, $exception->getCode());
        $this->assertSame($previousException, $exception->getPrevious());
        $this->assertSame($request, $exception->getRequest());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame(['test' => 'test'], $exception->getHandlerContext());
        $this->assertTrue($exception->hasResponse());
    }
}

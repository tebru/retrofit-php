<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal\ServiceMethod;

use GuzzleHttp\Psr7\AppendStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use LogicException;
use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\Internal\CallAdapter\DefaultCallAdapter;
use Tebru\Retrofit\Internal\Converter\DefaultRequestBodyConverter;
use Tebru\Retrofit\Internal\Converter\DefaultResponseBodyConverter;
use Tebru\Retrofit\Internal\ParameterHandler\BodyParamHandler;
use Tebru\Retrofit\Internal\ServiceMethod\DefaultServiceMethod;
use Tebru\Retrofit\Test\Mock\Unit\MockCall;

class ServiceMethodTest extends TestCase
{
    /**
     * @var DefaultServiceMethod
     */
    private $serviceMethod;

    public function setUp(): void
    {
        $this->serviceMethod = new DefaultServiceMethod(
            'POST',
            'http://example.com',
            '/foo/bar?q=test',
            ['content-type' => ['application/json']],
            [new BodyParamHandler(new DefaultRequestBodyConverter())],
            new DefaultCallAdapter(),
            new DefaultResponseBodyConverter(),
            new DefaultResponseBodyConverter()
        );
    }

    public function testCreateRequest()
    {
        $body = new AppendStream();
        $request = $this->serviceMethod->toRequest([$body]);

        $expected = new Request(
            'POST',
            'http://example.com/foo/bar?q=test',
            ['content-type' => ['application/json']],
            $body
        );

        self::assertEquals($expected, $request);
    }

    public function testCreateRequestDifferentParameters()
    {
        try {
            $this->serviceMethod->toRequest([]);
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Incompatible number of arguments. Expected 1 and got 0. This either ' .
                'means that the service method was not called with the correct number of parameters, ' .
                'or there is not an annotation for every parameter.',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testGetResponseBody()
    {
        $body = new AppendStream();
        $response = new Response(200, [], $body);
        $result = $this->serviceMethod->toResponseBody($response);

        self::assertSame($body, $result);
    }

    public function testGetErrorBody()
    {
        $body = new AppendStream();
        $response = new Response(200, [], $body);
        $result = $this->serviceMethod->toErrorBody($response);

        self::assertSame($body, $result);
    }

    public function testAdaptCall()
    {
        $call = new MockCall();
        $result = $this->serviceMethod->adapt($call);

        self::assertSame($call, $result);
    }
}

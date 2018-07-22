<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use RuntimeException;
use Tebru\Retrofit\Exception\ResponseHandlingFailedException;
use Tebru\Retrofit\Internal\HttpClientCall;
use Tebru\Retrofit\Response as RetrofitResponse;
use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\Test\Mock\Unit\Internal\HttpClientCallTest\HttpClientCallTestClientMock;
use Tebru\Retrofit\Test\Mock\Unit\Internal\HttpClientCallTest\HttpClientCallTestErrorBodyMock;
use Tebru\Retrofit\Test\Mock\Unit\Internal\HttpClientCallTest\HttpClientCallTestResponseBodyMock;
use Tebru\Retrofit\Test\Mock\Unit\Internal\HttpClientCallTest\HttpClientCallTestServiceMethodMock;
use Throwable;

class HttpClientCallTest extends TestCase
{
    public function testSync()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(204);
        $responseBody = new HttpClientCallTestResponseBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, $responseBody),
            []
        );

        $response = $call->execute();

        self::assertInstanceOf(RetrofitResponse::class, $response);
        self::assertSame(204, $response->raw()->getStatusCode());
        self::assertInstanceOf(HttpClientCallTestResponseBodyMock::class, $response->body());
        self::assertNull($response->errorBody());
    }

    public function testSyncError()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(500);
        $responseBody = new HttpClientCallTestErrorBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, null, $responseBody),
            []
        );

        $response = $call->execute();

        self::assertInstanceOf(RetrofitResponse::class, $response);
        self::assertSame(500, $response->raw()->getStatusCode());
        self::assertNull($response->body());
        self::assertInstanceOf(HttpClientCallTestErrorBodyMock::class, $response->errorBody());
    }

    public function testAsync()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(204);
        $responseBody = new HttpClientCallTestResponseBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, $responseBody),
            []
        );

        $call->enqueue(
            function (RetrofitResponse $response) {
                self::assertInstanceOf(RetrofitResponse::class, $response);
                self::assertSame(204, $response->raw()->getStatusCode());
                self::assertInstanceOf(HttpClientCallTestResponseBodyMock::class, $response->body());
                self::assertNull($response->errorBody());
            },
            function (Throwable $throwable) {
                self::fail('Error callback should not be called');
            }
        );

        $call->wait();
    }

    public function testAsyncError()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(500);
        $responseBody = new HttpClientCallTestErrorBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, null, $responseBody),
            []
        );

        $call->enqueue(
            function (RetrofitResponse $response) {
                self::assertInstanceOf(RetrofitResponse::class, $response);
                self::assertSame(500, $response->raw()->getStatusCode());
                self::assertNull($response->body());
                self::assertInstanceOf(HttpClientCallTestErrorBodyMock::class, $response->errorBody());
            },
            function (Throwable $throwable) {
                self::fail('Error callback should not be called');
            }
        );

        $call->wait();
    }

    public function testAsyncFailure()
    {
        $request = new Request('GET', 'http://example.com/');

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock(),
            new HttpClientCallTestServiceMethodMock($request),
            []
        );

        $call->enqueue(
            function (RetrofitResponse $response) {
                self::fail('Response callback should not be called');
            },
            function (Throwable $throwable) {
                self::assertInstanceOf(RuntimeException::class, $throwable);
            }
        );

        $call->wait();
    }

    public function testAsyncFailureThrowsException()
    {
        $request = new Request('GET', 'http://example.com/');

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock(),
            new HttpClientCallTestServiceMethodMock($request),
            []
        );

        $call->enqueue(
            function (RetrofitResponse $response) {
                self::fail('Response callback should not be called');
            }
        );

        try {
            $call->wait();
        } catch (RuntimeException $exception) {
            self::assertTrue(true);
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testSyncInvalidJsonResponseThrowsException()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(204, [], '{');

        $responseBody = new HttpClientCallTestResponseBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, $responseBody),
            []
        );

        try {
            $call->execute();
        } catch (ResponseHandlingFailedException $exception) {
            self::assertSame('GET', $exception->getRequest()->getMethod());
            self::assertSame('{', (string)$exception->getResponse()->getBody());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testSyncInvalidJsonErrorThrowsException()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(400, [], '{');

        $responseBody = new HttpClientCallTestResponseBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, $responseBody),
            []
        );

        try {
            $call->execute();
        } catch (ResponseHandlingFailedException $exception) {
            self::assertSame('GET', $exception->getRequest()->getMethod());
            self::assertSame('{', (string)$exception->getResponse()->getBody());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testAsyncInvalidJsonResponseThrowsExceptions()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(204, [], '{');
        $responseBody = new HttpClientCallTestResponseBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, $responseBody),
            []
        );

        $call->enqueue(function () {});

        try {
            $call->wait();
        } catch (ResponseHandlingFailedException $exception) {
            self::assertSame('GET', $exception->getRequest()->getMethod());
            self::assertSame('{', (string)$exception->getResponse()->getBody());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testAsyncInvalidJsonerrorThrowsExceptions()
    {
        $request = new Request('GET', 'http://example.com/');
        $response = new Response(400, [], '{');
        $responseBody = new HttpClientCallTestResponseBodyMock();

        $call = new HttpClientCall(
            new HttpClientCallTestClientMock($response),
            new HttpClientCallTestServiceMethodMock($request, $responseBody),
            []
        );

        $call->enqueue(function () {});

        try {
            $call->wait();
        } catch (ResponseHandlingFailedException $exception) {
            self::assertSame('GET', $exception->getRequest()->getMethod());
            self::assertSame('{', (string)$exception->getResponse()->getBody());
            return;
        }

        self::fail('Exception not thrown');
    }
}

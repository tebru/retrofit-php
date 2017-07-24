<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal;

use GuzzleHttp\Psr7\AppendStream;
use GuzzleHttp\Psr7\Response;
use Tebru\Retrofit\Internal\RetrofitResponse;
use PHPUnit\Framework\TestCase;

class RetrofitResponseTest extends TestCase
{
    public function testGettersSuccess()
    {
        $response = new Response();
        $responseBody = new AppendStream();
        $retrofitResponse = new RetrofitResponse($response, $responseBody, null);

        self::assertSame($response, $retrofitResponse->raw());
        self::assertSame(200, $retrofitResponse->code());
        self::assertSame('OK', $retrofitResponse->message());
        self::assertSame([], $retrofitResponse->headers());
        self::assertTrue($retrofitResponse->isSuccessful());
        self::assertSame($responseBody, $retrofitResponse->body());
        self::assertNull($retrofitResponse->errorBody());
    }

    public function testGettersFailure()
    {
        $response = new Response(500);
        $responseBody = new AppendStream();
        $retrofitResponse = new RetrofitResponse($response, null, $responseBody);

        self::assertSame($response, $retrofitResponse->raw());
        self::assertSame(500, $retrofitResponse->code());
        self::assertSame('Internal Server Error', $retrofitResponse->message());
        self::assertSame([], $retrofitResponse->headers());
        self::assertFalse($retrofitResponse->isSuccessful());
        self::assertNull($retrofitResponse->body());
        self::assertSame($responseBody, $retrofitResponse->errorBody());
    }
}

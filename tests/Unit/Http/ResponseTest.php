<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Http;

use Mockery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tebru\Retrofit\Adapter\DeserializerAdapter;
use Tebru\Retrofit\Http\Response;
use Tebru\Retrofit\Test\Mock\MockUser;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ResponseTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseTest extends MockeryTestCase
{
    public function testRawBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn('[]');

        $response = new Response($response, Response::FORMAT_RAW);
        $this->assertSame('[]', $response->body());
    }

    public function testArrayBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn('[]');

        $response = new Response($response, Response::FORMAT_ARRAY);
        $this->assertSame([], $response->body());
    }

    public function testObjectBodyNoContext()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn('[]');

        $user = new MockUser();
        $deserializerAdapter = Mockery::Mock(DeserializerAdapter::class);
        $deserializerAdapter->shouldReceive('deserialize')->times(1)->with('[]', MockUser::class, [])->andReturn($user);

        $response = new Response($response, MockUser::class, $deserializerAdapter);
        $this->assertSame($user, $response->body());
    }

    public function testObjectBodyWithContext()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn('[]');

        $user = new MockUser();
        $user->id = 1;
        $user->name = 'Foo';
        $user->email = 'foo@bar.com';

        $context = [
            'groups' => ['test'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => ['foo' => 'bar'],
            'depth' => 2,
        ];

        $deserializerAdapter = Mockery::Mock(DeserializerAdapter::class);
        $deserializerAdapter->shouldReceive('deserialize')->times(1)->with('[]', MockUser::class, $context)->andReturn($user);

        $response = new Response($response, MockUser::class, $deserializerAdapter, $context);
        $this->assertSame($user, $response->body());
    }

    public function testGetProtocolVersion()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getProtocolVersion')->times(1)->with();

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getProtocolVersion();
    }

    public function testWithProtocolVersion()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withProtocolVersion')->times(1)->with('1.1');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->withProtocolVersion('1.1');
    }

    public function testGetHeaders()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getHeaders')->times(1)->with();

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getHeaders();
    }

    public function testHasHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('hasHeader')->times(1)->with('Foo');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->hasHeader('Foo');
    }

    public function testGetHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getHeader')->times(1)->with('Foo');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getHeader('Foo');
    }

    public function testGetHeaderLine()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getHeaderLine')->times(1)->with('Foo');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getHeaderLine('Foo');
    }

    public function testWithHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withHeader')->times(1)->with('Foo', 'bar');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->withHeader('Foo', 'bar');
    }

    public function testWithAddedHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withAddedHeader')->times(1)->with('Foo', 'bar');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->withAddedHeader('Foo', 'bar');
    }

    public function testWithoutHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withoutHeader')->times(1)->with('Foo');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->withoutHeader('Foo');
    }

    public function testGetBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->with();

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getBody();
    }

    public function testWithBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $stream = Mockery::mock(StreamInterface::class);
        $response->shouldReceive('withBody')->times(1)->with($stream);

        $response = new Response($response, Response::FORMAT_RAW);
        $response->withBody($stream);
    }

    public function testGetStatusCode()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->times(1)->with();

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getStatusCode();
    }

    public function testWithStatus()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withStatus')->times(1)->with(1, 'foo');

        $response = new Response($response, Response::FORMAT_RAW);
        $response->withStatus(1, 'foo');
    }

    public function testGetReasonPhrase()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getReasonPhrase')->times(1)->with();

        $response = new Response($response, Response::FORMAT_RAW);
        $response->getReasonPhrase();
    }
}

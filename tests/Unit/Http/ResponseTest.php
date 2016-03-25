<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Http;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
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

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $this->assertSame('[]', $response->body());
    }

    public function testArrayBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn('[]');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_ARRAY, $serializer);
        $this->assertSame([], $response->body());
    }

    public function testObjectBodyNoContext()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn('[]');

        $user = new MockUser();
        $serializer = Mockery::Mock(SerializerInterface::class);
        $serializer->shouldReceive('deserialize')->times(1)->with('[]', MockUser::class, 'json', Mockery::type(DeserializationContext::class))->andReturn($user);

        $response = new Response($response, MockUser::class, $serializer);
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
        $serializer = Mockery::Mock(SerializerInterface::class);
        $serializer->shouldReceive('deserialize')->times(1)->with('[]', MockUser::class, 'json', Mockery::type(DeserializationContext::class))->andReturn($user);

        $context = [
            'groups' => ['test'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => ['foo' => 'bar'],
            'depth' => 2,
        ];

        $response = new Response($response, MockUser::class, $serializer, $context);
        $this->assertSame($user, $response->body());
    }

    public function testGetProtocolVersion()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getProtocolVersion')->times(1)->with();

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getProtocolVersion();
    }

    public function testWithProtocolVersion()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withProtocolVersion')->times(1)->with('1.1');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->withProtocolVersion('1.1');
    }

    public function testGetHeaders()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getHeaders')->times(1)->with();

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getHeaders();
    }

    public function testHasHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('hasHeader')->times(1)->with('Foo');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->hasHeader('Foo');
    }

    public function testGetHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getHeader')->times(1)->with('Foo');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getHeader('Foo');
    }

    public function testGetHeaderLine()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getHeaderLine')->times(1)->with('Foo');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getHeaderLine('Foo');
    }

    public function testWithHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withHeader')->times(1)->with('Foo', 'bar');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->withHeader('Foo', 'bar');
    }

    public function testWithAddedHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withAddedHeader')->times(1)->with('Foo', 'bar');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->withAddedHeader('Foo', 'bar');
    }

    public function testWithoutHeader()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withoutHeader')->times(1)->with('Foo');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->withoutHeader('Foo');
    }

    public function testGetBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->with();

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getBody();
    }

    public function testWithBody()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $stream = Mockery::mock(StreamInterface::class);
        $response->shouldReceive('withBody')->times(1)->with($stream);

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->withBody($stream);
    }

    public function testGetStatusCode()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->times(1)->with();

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getStatusCode();
    }

    public function testWithStatus()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('withStatus')->times(1)->with(1, 'foo');

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->withStatus(1, 'foo');
    }

    public function testGetReasonPhrase()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getReasonPhrase')->times(1)->with();

        $serializer = Mockery::Mock(SerializerInterface::class);

        $response = new Response($response, Response::FORMAT_RAW, $serializer);
        $response->getReasonPhrase();
    }
}

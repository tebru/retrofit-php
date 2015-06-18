<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Handler;

use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Handler\BodyHandler;
use Tebru\Retrofit\Handler\HeaderHandler;
use Tebru\Retrofit\Handler\HeadersHandler;
use Tebru\Retrofit\Handler\HttpRequestHandler;
use Tebru\Retrofit\Handler\JsonBodyHandler;
use Tebru\Retrofit\Handler\PartHandler;
use Tebru\Retrofit\Handler\QueryHandler;
use Tebru\Retrofit\Handler\QueryMapHandler;
use Tebru\Retrofit\Handler\ReturnsHandler;
use Tebru\Retrofit\Handler\Serializer\SerializationContextHandler;
use Tebru\Retrofit\Handler\UrlHandler;
use Tebru\Retrofit\Model\Method;

/**
 * Class AnnotationHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AnnotationHandlerTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    public function testBodyHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getValue')->times(1)->withNoArgs()->andReturn('value');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('setOptions')->times(1)->with(['body' => 'value'])->andReturnNull();

        $handler = new BodyHandler();
        $handler->handle($method, $annotation);
    }

    public function testHeaderHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getKey')->times(1)->withNoArgs()->andReturn('key');
        $annotation->shouldReceive('getValue')->times(1)->withNoArgs()->andReturn('value');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('addHeaders')->times(1)->with(['key' => 'value'])->andReturnNull();

        $handler = new HeaderHandler();
        $handler->handle($method, $annotation);
    }

    public function testHeadersHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getHeaders')->times(1)->withNoArgs()->andReturn(['Foo' => 'bar', 'Baz' => 'boing']);
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('addHeaders')->times(1)->with(['Foo' => 'bar', 'Baz' => 'boing'])->andReturnNull();

        $handler = new HeadersHandler();
        $handler->handle($method, $annotation);
    }

    public function testHttpRequestHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getType')->times(1)->withNoArgs()->andReturn('type');
        $annotation->shouldReceive('getPath')->times(1)->withNoArgs()->andReturn('path');
        $annotation->shouldReceive('getQueries')->times(1)->withNoArgs()->andReturn(['queries']);
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('setType')->times(1)->with('type')->andReturnNull();
        $method->shouldReceive('setPath')->times(1)->with('path')->andReturnNull();
        $method->shouldReceive('addQueries')->times(1)->with(['queries'])->andReturnNull();

        $handler = new HttpRequestHandler();
        $handler->handle($method, $annotation);
    }

    public function testJsonBody()
    {
        $annotation = Mockery::mock(Body::class);
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('setJsonBody')->times(1)->with(true)->andReturnNull();

        $handler = new JsonBodyHandler();
        $handler->handle($method, $annotation);
    }

    public function testPartHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getKey')->times(1)->withNoArgs()->andReturn('key');
        $annotation->shouldReceive('getValue')->times(1)->withNoArgs()->andReturn('value');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('addParts')->times(1)->with(['key' => 'value'])->andReturnNull();

        $handler = new PartHandler();
        $handler->handle($method, $annotation);
    }

    public function testQueryHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getKey')->times(1)->withNoArgs()->andReturn('key');
        $annotation->shouldReceive('getValue')->times(1)->withNoArgs()->andReturn('value');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('addQueries')->times(1)->with(['key' => 'value'])->andReturnNull();

        $handler = new QueryHandler();
        $handler->handle($method, $annotation);
    }

    public function testQueryMapHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getValue')->times(1)->withNoArgs()->andReturn('value');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('addQueryMap')->times(1)->with('value')->andReturnNull();

        $handler = new QueryMapHandler();
        $handler->handle($method, $annotation);
    }

    public function testReturnsHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getReturn')->times(1)->withNoArgs()->andReturn('return');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('setReturn')->times(1)->with('return')->andReturnNull();

        $handler = new ReturnsHandler();
        $handler->handle($method, $annotation);
    }

    public function testUrlHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getValue')->times(1)->withNoArgs()->andReturn('value');
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('setUrl')->times(1)->with('value')->andReturnNull();

        $handler = new UrlHandler();
        $handler->handle($method, $annotation);
    }

    public function testSerializationContextHandler()
    {
        $annotation = Mockery::mock(Body::class);
        $annotation->shouldReceive('getGroups')->times(1)->withNoArgs()->andReturn(['foo']);
        $annotation->shouldReceive('getSerializeNull')->times(1)->withNoArgs()->andReturn(true);
        $annotation->shouldReceive('getVersion')->times(1)->withNoArgs()->andReturn(1);
        $annotation->shouldReceive('getAttributes')->times(1)->withNoArgs()->andReturn(['bar' => 'baz']);
        $method = Mockery::mock(Method::class);
        $method->shouldReceive('setSerializationContext')->times(1)->with([
            'groups' => ['foo'],
            'serializeNull' => true,
            'version' => 1,
            'attributes' => ['bar' => 'baz'],
        ])->andReturnNull();

        $handler = new SerializationContextHandler();
        $handler->handle($method, $annotation);
    }
}

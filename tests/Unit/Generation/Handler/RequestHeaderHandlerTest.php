<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\RequestHeaderHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RequestHeaderHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestHeaderHandlerTest extends MockeryTestCase
{
    public function testAnnotationNotExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(false);
        $methodBodyBuilder->shouldReceive('setHeaders')->times(1)->with(['Content-Type' => 'application/x-www-form-urlencoded'])->andReturnNull();

        $handler = new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationsExistsJson()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $headerAnnotation = Mockery::mock(Header::class);
        $headersAnnotation = Mockery::mock(Headers::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Header::NAME)->andReturn([$headerAnnotation]);
        $headerAnnotation->shouldReceive('getRequestKey')->times(1)->withNoArgs()->andReturn('foo');
        $headerAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$foo');

        $annotationCollection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Headers::NAME)->andReturn($headersAnnotation);
        $headersAnnotation->shouldReceive('getHeaders')->times(1)->withNoArgs()->andReturn(['headerskey' => 'headersvalue']);

        $annotationCollection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(true);

        $methodBodyBuilder->shouldReceive('setHeaders')->times(1)->with(['headerskey' => 'headersvalue', 'foo' => '$foo', 'Content-Type' => 'application/json'])->andReturnNull();
        $methodBodyBuilder->shouldReceive('setJsonEncode')->times(1)->with(true)->andReturnNull();

        $handler = new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationsExistsMultipart()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $headerAnnotation = Mockery::mock(Header::class);
        $headersAnnotation = Mockery::mock(Headers::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Header::NAME)->andReturn([$headerAnnotation]);
        $headerAnnotation->shouldReceive('getRequestKey')->times(1)->withNoArgs()->andReturn('foo');
        $headerAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$foo');

        $annotationCollection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Headers::NAME)->andReturn($headersAnnotation);
        $headersAnnotation->shouldReceive('getHeaders')->times(1)->withNoArgs()->andReturn(['headerskey' => 'headersvalue']);

        $annotationCollection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(true);

        $methodBodyBuilder->shouldReceive('setHeaders')->times(1)->with(['headerskey' => 'headersvalue', 'foo' => '$foo', 'Content-Type' => 'multipart/form-data'])->andReturnNull();

        $handler = new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

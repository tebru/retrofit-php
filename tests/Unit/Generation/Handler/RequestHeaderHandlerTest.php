<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Annotation\Part;
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
        $annotationCollection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(false);
        $methodBodyBuilder->shouldReceive('setHeaders')->times(1)->with(['Content-Type' => 'application/x-www-form-urlencoded'])->andReturnNull();
        $methodBodyBuilder->shouldReceive('setFormUrlEncoded')->times(1)->with(true)->andReturnNull();

        $handler = new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function testAnnotationNotExistsWithBody()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(true);

        $handler = new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationExistsFormUrlEncoded()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(true);
        $methodBodyBuilder->shouldReceive('setHeaders')->times(1)->with(['Content-Type' => 'application/x-www-form-urlencoded'])->andReturnNull();
        $methodBodyBuilder->shouldReceive('setFormUrlEncoded')->times(1)->with(true)->andReturnNull();

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
        $multipartAnnotation = Mockery::mock(Multipart::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Header::NAME)->andReturn([$headerAnnotation]);
        $headerAnnotation->shouldReceive('getRequestKey')->times(1)->withNoArgs()->andReturn('foo');
        $headerAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$foo');

        $annotationCollection->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Headers::NAME)->andReturn($headersAnnotation);
        $annotationCollection->shouldReceive('get')->times(1)->with(Multipart::NAME)->andReturn($multipartAnnotation);
        $headersAnnotation->shouldReceive('getHeaders')->times(1)->withNoArgs()->andReturn(['headerskey' => 'headersvalue']);

        $annotationCollection->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(FormUrlEncoded::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Multipart::NAME)->andReturn(true);

        $methodBodyBuilder->shouldReceive('setHeaders')->times(1)->with(['headerskey' => 'headersvalue', 'foo' => '$foo', 'Content-Type' => 'multipart/form-data; boundary=1234'])->andReturnNull();
        $methodBodyBuilder->shouldReceive('setBoundaryId')->times(1)->with('1234')->andReturnNull();
        $methodBodyBuilder->shouldReceive('setMultipartEncoded')->times(1)->with(true)->andReturnNull();

        $multipartAnnotation->shouldReceive('getBoundary')->times(2)->with()->andReturn('1234');

        $handler = new RequestHeaderHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

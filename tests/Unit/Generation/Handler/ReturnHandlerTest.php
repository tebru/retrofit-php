<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\ReturnHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ReturnHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnHandlerTest extends MockeryTestCase
{
    public function testAnnotationNotExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(false);

        $handler = new ReturnHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $returnAnnotation = Mockery::mock(Returns::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Returns::NAME)->andReturn($returnAnnotation);
        $returnAnnotation->shouldReceive('getReturn')->times(1)->withNoArgs()->andReturn('array');
        $methodBodyBuilder->shouldReceive('setReturnType')->times(1)->with('array')->andReturnNull();

        $handler = new ReturnHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testResponseWithType()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $returnAnnotation = Mockery::mock(Returns::class);
        $responseAnnotation = Mockery::mock(ResponseType::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('exists')->times(1)->with(ResponseType::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Returns::NAME)->andReturn($returnAnnotation);
        $annotationCollection->shouldReceive('get')->times(1)->with(ResponseType::NAME)->andReturn($responseAnnotation);
        $returnAnnotation->shouldReceive('getReturn')->times(1)->withNoArgs()->andReturn('Response');
        $responseAnnotation->shouldReceive('getType')->times(1)->withNoArgs()->andReturn('array');
        $methodBodyBuilder->shouldReceive('setReturnType')->times(1)->with('Response')->andReturnNull();
        $methodBodyBuilder->shouldReceive('setResponseType')->times(1)->with('array')->andReturnNull();

        $handler = new ReturnHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     * @expectedExceptionMessage When using a Response return type, an @ResponseType must also be set.
     */
    public function testResponseWithoutTypeThrowsException()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $returnAnnotation = Mockery::mock(Returns::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('exists')->times(1)->with(ResponseType::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('get')->times(1)->with(Returns::NAME)->andReturn($returnAnnotation);
        $returnAnnotation->shouldReceive('getReturn')->times(1)->withNoArgs()->andReturn('Response');
        $methodBodyBuilder->shouldReceive('setReturnType')->times(1)->with('Response')->andReturnNull();

        $handler = new ReturnHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

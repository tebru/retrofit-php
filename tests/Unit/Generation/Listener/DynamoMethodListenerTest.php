<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Listener;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Event\MethodEvent;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Generation\Builder\Factory\MethodBodyBuilderFactory;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\AsyncHandler;
use Tebru\Retrofit\Generation\Handler\BaseUrlHandler;
use Tebru\Retrofit\Generation\Handler\Factory\HandlerFactory;
use Tebru\Retrofit\Generation\Handler\RequestBodyHandler;
use Tebru\Retrofit\Generation\Handler\RequestHeaderHandler;
use Tebru\Retrofit\Generation\Handler\RequestUrlHandler;
use Tebru\Retrofit\Generation\Handler\ReturnHandler;
use Tebru\Retrofit\Generation\Handler\SerializationContextHandler;
use Tebru\Retrofit\Generation\Listener\DynamoMethodListener;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class DynamoMethodListenerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoMethodListenerTest extends MockeryTestCase
{
    public function testCanCreateListener()
    {
        $handlerFactory = Mockery::mock(HandlerFactory::class);
        $methodBodyBuilderFactory = Mockery::mock(MethodBodyBuilderFactory::class);
        $listener = new DynamoMethodListener($handlerFactory, $methodBodyBuilderFactory);

        $this->assertInstanceOf(DynamoMethodListener::class, $listener);
    }

    public function testHandleEvent()
    {
        $handlerFactory = Mockery::mock(HandlerFactory::class);
        $methodBodyBuilderFactory = Mockery::mock(MethodBodyBuilderFactory::class);
        $event = Mockery::mock(MethodEvent::class);
        $methodModel = Mockery::mock(MethodModel::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $baseUrlHandler = Mockery::mock(BaseUrlHandler::class);
        $serializationContextHandler = Mockery::mock(SerializationContextHandler::class);
        $requestUrlHandler = Mockery::mock(RequestUrlHandler::class);
        $requestHeaderHandler = Mockery::mock(RequestHeaderHandler::class);
        $requestBodyHandler = Mockery::mock(RequestBodyHandler::class);
        $returnHandler = Mockery::mock(ReturnHandler::class);
        $asyncHandler = Mockery::mock(AsyncHandler::class);

        $event->shouldReceive('getMethodModel')->times(1)->withNoArgs()->andReturn($methodModel);
        $event->shouldReceive('getAnnotationCollection')->times(1)->withNoArgs()->andReturn($annotationCollection);
        $methodBodyBuilderFactory->shouldReceive('make')->times(1)->withNoArgs()->andReturn($methodBodyBuilder);
        $handlerFactory->shouldReceive('baseUrl')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($baseUrlHandler);
        $handlerFactory->shouldReceive('serializationContext')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($serializationContextHandler);
        $handlerFactory->shouldReceive('requestUrl')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($requestUrlHandler);
        $handlerFactory->shouldReceive('requestHeader')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($requestHeaderHandler);
        $handlerFactory->shouldReceive('requestBody')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($requestBodyHandler);
        $handlerFactory->shouldReceive('returns')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($returnHandler);
        $handlerFactory->shouldReceive('asyncCallback')->times(1)->with($methodModel, $methodBodyBuilder, $annotationCollection)->andReturn($asyncHandler);
        $baseUrlHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $serializationContextHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $requestUrlHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $requestHeaderHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $requestBodyHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $returnHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $asyncHandler->shouldReceive('handle')->times(1)->withNoArgs()->andReturnNull();
        $methodBodyBuilder->shouldReceive('build')->times(1)->withNoArgs()->andReturn('body');
        $methodModel->shouldReceive('setBody')->times(1)->with('body')->andReturnNull();

        $listener = new DynamoMethodListener($handlerFactory, $methodBodyBuilderFactory);

        $this->assertNull($listener($event));
    }
}

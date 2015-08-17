<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler\Factory;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\BaseUrlHandler;
use Tebru\Retrofit\Generation\Handler\Factory\HandlerFactory;
use Tebru\Retrofit\Generation\Handler\RequestBodyHandler;
use Tebru\Retrofit\Generation\Handler\RequestHeaderHandler;
use Tebru\Retrofit\Generation\Handler\RequestUrlHandler;
use Tebru\Retrofit\Generation\Handler\ReturnHandler;
use Tebru\Retrofit\Generation\Handler\SerializationContextHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class HandlerFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HandlerFactoryTest extends MockeryTestCase
{
    public function testCanCreateBaseUrlHandler()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $handlerFactory = new HandlerFactory();
        $handler = $handlerFactory->baseUrl($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertInstanceOf(BaseUrlHandler::class, $handler);
    }

    public function testCanCreateRequestBodyHandler()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $handlerFactory = new HandlerFactory();
        $handler = $handlerFactory->requestBody($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertInstanceOf(RequestBodyHandler::class, $handler);
    }

    public function testCanCreateRequestHeaderHandler()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $handlerFactory = new HandlerFactory();
        $handler = $handlerFactory->requestHeader($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertInstanceOf(RequestHeaderHandler::class, $handler);
    }

    public function testCanCreateRequestUrlHandler()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $handlerFactory = new HandlerFactory();
        $handler = $handlerFactory->requestUrl($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertInstanceOf(RequestUrlHandler::class, $handler);
    }

    public function testCanCreateReturnHandler()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $handlerFactory = new HandlerFactory();
        $handler = $handlerFactory->returns($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertInstanceOf(ReturnHandler::class, $handler);
    }

    public function testCanCreateSerializationContextHandler()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $handlerFactory = new HandlerFactory();
        $handler = $handlerFactory->serializationContext($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertInstanceOf(SerializationContextHandler::class, $handler);
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\ClassModel;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\AsyncHandler;
use Tebru\Retrofit\Test\Mock\Service\MockServiceAsync;
use Tebru\Retrofit\Test\Mock\Service\MockServiceBaseUrl;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class AsyncHandlerTest
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class AsyncHandlerTest extends MockeryTestCase
{
    public function testCallbackExists()
    {
        $classModel = Mockery::mock(ClassModel::class);
        $methodModel = Mockery::mock(MethodModel::class);
        $parameterModel = Mockery::mock(ParameterModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $classModel->shouldReceive('getInterface')->once()->andReturn(MockServiceAsync::class);
        $methodModel->shouldReceive('getClassModel')->once()->andReturn($classModel);
        $methodModel->shouldReceive('getParameters')->once()->andReturn([$parameterModel]);
        $parameterModel->shouldReceive('getTypeHint')->once()->andReturn('\Tebru\Retrofit\Http\Callback');
        $parameterModel->shouldReceive('getName')->once()->andReturn('foo');
        $parameterModel->shouldReceive('isOptional')->once()->andReturn(true);

        $methodBodyBuilder->shouldReceive('setCallback')->times(1)->with('$foo')->andReturnNull();
        $methodBodyBuilder->shouldReceive('setIsCallbackOptional')->times(1)->with(true)->andReturnNull();

        $handler = new AsyncHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testCallbackNotExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $parameterModel = Mockery::mock(ParameterModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $methodModel->shouldNotReceive('getClassModel');
        $methodModel->shouldReceive('getParameters')->once()->andReturn([$parameterModel]);
        $parameterModel->shouldReceive('getTypeHint')->once()->andReturn('\Tebru\Retrofit\Http\NotCallback');

        $handler = new AsyncHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     */
    public function testAsyncHandlerException()
    {
        $classModel = Mockery::mock(ClassModel::class);
        $methodModel = Mockery::mock(MethodModel::class);
        $parameterModel = Mockery::mock(ParameterModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $classModel->shouldReceive('getInterface')->once()->andReturn(MockServiceBaseUrl::class);
        $methodModel->shouldReceive('getClassModel')->once()->andReturn($classModel);
        $methodModel->shouldReceive('getParameters')->once()->andReturn([$parameterModel]);
        $parameterModel->shouldReceive('getTypeHint')->once()->andReturn('\Tebru\Retrofit\Http\Callback');

        $parameterModel->shouldNotReceive('getName');
        $parameterModel->shouldNotReceive('isOptional');

        $methodBodyBuilder->shouldNotReceive('setCallback');
        $methodBodyBuilder->shouldNotReceive('setIsCallbackOptional');

        $handler = new AsyncHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

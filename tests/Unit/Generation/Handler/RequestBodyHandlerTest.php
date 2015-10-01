<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\RequestBodyHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RequestBodyHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestBodyHandlerTest extends MockeryTestCase
{
    public function testNoAnnotationExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(false);

        $handler = new RequestBodyHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testBodyAnnotationsExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $bodyAnnotation = Mockery::mock(Body::class);
        $parameter = Mockery::mock(ParameterModel::class);
        $partAnnotation = Mockery::mock(Part::class);


        $annotationCollection->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Body::NAME)->andReturn($bodyAnnotation);
        $bodyAnnotation->shouldReceive('getVariableName')->times(1)->withNoArgs()->andReturn('foo');
        $bodyAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$foo');
        $methodModel->shouldReceive('getParameter')->times(1)->with('foo')->andReturn($parameter);
        $parameter->shouldReceive('isObject')->times(1)->withNoArgs()->andReturn(true);
        $parameter->shouldReceive('isOptional')->times(1)->withNoArgs()->andReturn(true);
        $parameter->shouldReceive('getDefaultValue')->times(1)->withNoArgs()->andReturn(null);
        $parameter->shouldReceive('isArray')->times(1)->withNoArgs()->andReturn(false);
        $methodBodyBuilder->shouldReceive('setBodyIsObject')->times(1)->with(true)->andReturnNull();
        $methodBodyBuilder->shouldReceive('setBodyIsOptional')->times(1)->with(true)->andReturnNull();
        $methodBodyBuilder->shouldReceive('setBodyDefaultValue')->times(1)->with('null')->andReturnNull();
        $methodBodyBuilder->shouldReceive('setBodyIsArray')->times(1)->with(false)->andReturnNull();
        $methodBodyBuilder->shouldReceive('setBody')->times(1)->with('$foo')->andReturnNull();

        $annotationCollection->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Part::NAME)->andReturn([$partAnnotation]);
        $partAnnotation->shouldReceive('getRequestKey')->times(1)->withNoArgs()->andReturn('foo');
        $partAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$foo');
        $methodBodyBuilder->shouldReceive('setBodyParts')->times(1)->with(['foo' => '$foo'])->andReturnNull();

        $handler = new RequestBodyHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

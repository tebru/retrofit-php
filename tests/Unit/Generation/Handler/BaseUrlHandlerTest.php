<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\BaseUrlHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BaseUrlHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BaseUrlHandlerTest extends MockeryTestCase
{
    public function testAnnotationNotExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(BaseUrl::NAME)->andReturn(false);

        $handler = new BaseUrlHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $baseUrlAnnotation = Mockery::mock(BaseUrl::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(BaseUrl::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(BaseUrl::NAME)->andReturn($baseUrlAnnotation);
        $baseUrlAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$foo');
        $methodBodyBuilder->shouldReceive('setBaseUrl')->times(1)->with('$foo')->andReturnNull();

        $handler = new BaseUrlHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\RequestUrlHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RequestUrlHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestUrlHandlerTest extends MockeryTestCase
{
    public function testAnnotationNotExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $requestAnnotation = Mockery::mock(GET::class);

        $annotationCollection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($requestAnnotation);
        $requestAnnotation->shouldReceive('getType')->times(1)->withNoArgs()->andReturn('get');
        $requestAnnotation->shouldReceive('getPath')->times(1)->withNoArgs()->andReturn('/path');
        $methodBodyBuilder->shouldReceive('setRequestMethod')->times(1)->with('get')->andReturnNull();
        $methodBodyBuilder->shouldReceive('setUri')->times(1)->with('/path')->andReturnNull();

        $requestAnnotation->shouldReceive('getQueries')->times(1)->withNoArgs()->andReturn(['foo' => 'foo']);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(false);
        $methodBodyBuilder->shouldReceive('setQueries')->times(1)->with(['foo' => 'foo'])->andReturnNull();

        $annotationCollection->shouldReceive('exists')->times(1)->with(QueryMap::NAME)->andReturn(false);

        $handler = new RequestUrlHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationsExist()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $requestAnnotation = Mockery::mock(GET::class);
        $queryAnnotation = Mockery::mock(Query::class);
        $queryMapAnnotation = Mockery::mock(QueryMap::class);

        $annotationCollection->shouldReceive('get')->times(1)->with(HttpRequest::NAME)->andReturn($requestAnnotation);
        $requestAnnotation->shouldReceive('getType')->times(1)->withNoArgs()->andReturn('get');
        $requestAnnotation->shouldReceive('getPath')->times(1)->withNoArgs()->andReturn('/path');
        $methodBodyBuilder->shouldReceive('setRequestMethod')->times(1)->with('get')->andReturnNull();
        $methodBodyBuilder->shouldReceive('setUri')->times(1)->with('/path')->andReturnNull();

        $requestAnnotation->shouldReceive('getQueries')->times(1)->withNoArgs()->andReturn(['foo' => 'foo']);
        $annotationCollection->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(Query::NAME)->andReturn([$queryAnnotation]);
        $queryAnnotation->shouldReceive('getRequestKey')->times(1)->withNoArgs()->andReturn('querykey');
        $queryAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$queryvar');
        $methodBodyBuilder->shouldReceive('setQueries')->times(1)->with(['foo' => 'foo', 'querykey' => '$queryvar'])->andReturnNull();

        $annotationCollection->shouldReceive('exists')->times(1)->with(QueryMap::NAME)->andReturn(true);

        $handler = new RequestUrlHandler($methodModel, $methodBodyBuilder, $annotationCollection);
        $annotationCollection->shouldReceive('get')->times(1)->with(QueryMap::NAME)->andReturn($queryMapAnnotation);
        $queryMapAnnotation->shouldReceive('getVariable')->times(1)->withNoArgs()->andReturn('$querymapvar');
        $methodBodyBuilder->shouldReceive('setQueryMap')->times(1)->with('$querymapvar')->andReturnNull();

        $this->assertNull($handler->handle());
    }
}

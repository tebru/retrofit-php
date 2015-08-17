<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Generation\Handler\SerializationContextHandler;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class SerializationContextHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SerializationContextHandlerTest extends MockeryTestCase
{
    public function testAnnotationNotExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(SerializationContext::NAME)->andReturn(false);
        $annotationCollection->shouldReceive('exists')->times(1)->with(DeserializationContext::NAME)->andReturn(false);

        $handler = new SerializationContextHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }

    public function testAnnotationExists()
    {
        $methodModel = Mockery::mock(MethodModel::class);
        $methodBodyBuilder = Mockery::mock(MethodBodyBuilder::class);
        $annotationCollection = Mockery::mock(AnnotationCollection::class);
        $serializationContextAnnotation = Mockery::mock(SerializationContext::class);
        $deserializationContextAnnotation = Mockery::mock(DeserializationContext::class);

        $annotationCollection->shouldReceive('exists')->times(1)->with(SerializationContext::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(SerializationContext::NAME)->andReturn($serializationContextAnnotation);
        $serializationContextAnnotation->shouldReceive('getGroups')->times(1)->withNoArgs()->andReturn([]);
        $serializationContextAnnotation->shouldReceive('getVersion')->times(1)->withNoArgs()->andReturn(1);
        $serializationContextAnnotation->shouldReceive('getSerializeNull')->times(1)->withNoArgs()->andReturn(true);
        $serializationContextAnnotation->shouldReceive('getEnableMaxDepthChecks')->times(1)->withNoArgs()->andReturn(true);
        $serializationContextAnnotation->shouldReceive('getAttributes')->times(1)->withNoArgs()->andReturn(['foo' => 'bar']);
        $context = [
            'groups' => [],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => ['foo' => 'bar'],
        ];
        $methodBodyBuilder->shouldReceive('setSerializationContext')->times(1)->with($context)->andReturnNull();

        $annotationCollection->shouldReceive('exists')->times(1)->with(DeserializationContext::NAME)->andReturn(true);
        $annotationCollection->shouldReceive('get')->times(1)->with(DeserializationContext::NAME)->andReturn($deserializationContextAnnotation);
        $deserializationContextAnnotation->shouldReceive('getGroups')->times(1)->withNoArgs()->andReturn([]);
        $deserializationContextAnnotation->shouldReceive('getVersion')->times(1)->withNoArgs()->andReturn(1);
        $deserializationContextAnnotation->shouldReceive('getSerializeNull')->times(1)->withNoArgs()->andReturn(true);
        $deserializationContextAnnotation->shouldReceive('getEnableMaxDepthChecks')->times(1)->withNoArgs()->andReturn(true);
        $deserializationContextAnnotation->shouldReceive('getAttributes')->times(1)->withNoArgs()->andReturn(['foo' => 'bar']);
        $deserializationContextAnnotation->shouldReceive('getDepth')->times(1)->withNoArgs()->andReturn(2);
        $context['depth'] = 2;
        $methodBodyBuilder->shouldReceive('setDeserializationContext')->times(1)->with($context)->andReturnNull();

        $handler = new SerializationContextHandler($methodModel, $methodBodyBuilder, $annotationCollection);

        $this->assertNull($handler->handle());
    }
}

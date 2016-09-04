<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Listener;

use Mockery;
use Tebru\Dynamo\Collection\AnnotationCollection;
use Tebru\Dynamo\Event\MethodEvent;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\Serializer\DeserializerContext;
use Tebru\Retrofit\Generation\Listener\DynamoMethodListener;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class DynamoMethodListenerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoMethodListenerTest extends MockeryTestCase
{
    public function testHandleEvent()
    {
        $method = Mockery::mock(MethodModel::class);
        $annotations = Mockery::mock(AnnotationCollection::class);
        $requestAnnotation = Mockery::mock(GET::class);

        $annotations->shouldReceive('exists')->times(1)->with(BaseUrl::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(QueryMap::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(Query::NAME)->andReturn(false);
        $annotations->shouldReceive('get')->times(3)->with(HttpRequest::NAME)->andReturn($requestAnnotation);
        $annotations->shouldReceive('exists')->times(1)->with(Header::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(Headers::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(JsonBody::NAME)->andReturn(true);
        $annotations->shouldReceive('exists')->times(1)->with(Body::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(Part::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(Returns::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(ResponseType::NAME)->andReturn(false);
        $annotations->shouldReceive('exists')->times(1)->with(DeserializerContext::NAME)->andReturn(false);

        $requestAnnotation->shouldReceive('getPath')->times(1)->with()->andReturn('/get');
        $requestAnnotation->shouldReceive('getQueries')->times(1)->with()->andReturn([]);
        $requestAnnotation->shouldReceive('getType')->times(1)->with()->andReturn('GET');

        $method->shouldReceive('getParameters')->times(2)->with()->andReturn([]);
        $method->shouldReceive('setBody')->times(1)->andReturn([]);

        $listener = new DynamoMethodListener();
        $listener(new MethodEvent($method, $annotations));
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Mockery\MockInterface;
use Tebru\Retrofit\Generation\Handler\ResponseHandler;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;

/**
 * Class ResponseHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseHandlerTest extends AbstractHandlerTest
{
    public function testResponseSync()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getRequestMethod')->times(1)->with()->andReturn('GET');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testResponseAsyncNotOptional()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn('$callback');
        $annotationProvider->shouldReceive('isCallbackOptional')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getRequestMethod')->times(1)->with()->andReturn('GET');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testResponseAsyncOptional()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn('$callback');
        $annotationProvider->shouldReceive('isCallbackOptional')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getRequestMethod')->times(1)->with()->andReturn('GET');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    private function assert(MockInterface $annotationProvider, $method)
    {
        $context = $this->getHandlerContext($annotationProvider);

        $handler = new ResponseHandler();
        $handler($context);

        $this->assertResponse($method);
    }
}

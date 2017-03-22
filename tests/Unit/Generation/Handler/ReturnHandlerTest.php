<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Mockery\MockInterface;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\Generation\Handler\ReturnHandler;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;
use Tebru\Retrofit\Http\Response;
use Tebru\Retrofit\Test\Mock\MockUser;

/**
 * Class ReturnHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnHandlerTest extends AbstractHandlerTest
{
    public function testReturnSyncArray()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getReturnType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getResponseType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getDeserializationContext')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testReturnSyncRaw()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getReturnType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getResponseType')->times(2)->with()->andReturn('raw');
        $annotationProvider->shouldReceive('getDeserializationContext')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testReturnSyncObject()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getReturnType')->times(2)->with()->andReturn(MockUser::class);
        $annotationProvider->shouldReceive('getResponseType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getDeserializationContext')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testReturnSyncResponse()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getReturnType')->times(2)->with()->andReturn('Response');
        $annotationProvider->shouldReceive('getResponseType')->times(2)->with()->andReturn(MockUser::class);
        $annotationProvider->shouldReceive('getDeserializationContext')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     * @expectedExceptionMessage A method return a Response must include a @ResponseType annotation.
     */
    public function testReturnSyncResponseThrowsException()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getReturnType')->times(2)->with()->andReturn('Response');
        $annotationProvider->shouldReceive('getResponseType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getDeserializationContext')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testReturnAsyncNotOptional()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn('$callback');
        $annotationProvider->shouldReceive('isCallbackOptional')->times(1)->with()->andReturn(false);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testReturnAsyncOptional()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getCallback')->times(1)->with()->andReturn('$callback');
        $annotationProvider->shouldReceive('isCallbackOptional')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getReturnType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getResponseType')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getDeserializationContext')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    private function assert(MockInterface $annotationProvider, $method)
    {
        $context = $this->getHandlerContext($annotationProvider);

        $handler = new ReturnHandler();
        $handler($context);

        $this->assertResponse($method);
    }
}

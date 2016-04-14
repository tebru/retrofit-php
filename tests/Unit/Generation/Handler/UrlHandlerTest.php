<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Mockery\MockInterface;
use PhpParser\Lexer;
use Tebru\Retrofit\Generation\Handler\UrlHandler;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;

/**
 * Class UrlHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class UrlHandlerTest extends AbstractHandlerTest
{
    public function testUrlNoQueryOrQueryMap()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getBaseUrl')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getQueryMap')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getQueries')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getRequestUri')->times(1)->with()->andReturn('/get');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testUrlWithQueryMapNoQueries()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getBaseUrl')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getQueryMap')->times(1)->with()->andReturn('$retrofitQueryMap');
        $annotationProvider->shouldReceive('getQueries')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getRequestUri')->times(1)->with()->andReturn('/get');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testUrlWithQueryMapAndQueries()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getBaseUrl')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getQueryMap')->times(1)->with()->andReturn('$retrofitQueryMap');
        $annotationProvider->shouldReceive('getQueries')->times(1)->with()->andReturn(['foo' => 'bar']);
        $annotationProvider->shouldReceive('getRequestUri')->times(1)->with()->andReturn('/get');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testUrlWithQueriesNoQueryMap()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getBaseUrl')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getQueryMap')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getQueries')->times(1)->with()->andReturn(['foo' => 'bar']);
        $annotationProvider->shouldReceive('getRequestUri')->times(1)->with()->andReturn('/get');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    private function assert(MockInterface $annotationProvider, $method)
    {
        $context = $this->getHandlerContext($annotationProvider);

        $handler = new UrlHandler();
        $handler($context);

        $this->assertResponse($method);
    }
}

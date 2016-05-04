<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_Error_Deprecated;
use Tebru\Retrofit\Generation\Handler\HeaderHandler;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;

/**
 * Class HeaderHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeaderHandlerTest extends AbstractHandlerTest
{
    public function testHeadersMultipart()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isMultipart')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getMultipartBoundary')->times(1)->with()->andReturn('fooboundary');

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testHeadersFormEncoded()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testHeadersJsonEncoded()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     */
    public function testHeadersDefaultWithBodyThrowsException()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isMultipart')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testHeadersDefaultIgnoreDeprecatedWithoutBody()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isMultipart')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(false);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testHeadersDefaultWithBodyThrowsExceptionIgnored()
    {
        $this->disableDeprecationWarning();

        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isMultipart')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);

        $this->enableDeprecationWarning();
    }

    public function testHeadersJsonWithStatic()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(['foo' => 'bar']);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testHeadersJsonWithProvided()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(['Accept-Content' => '$content']);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testHeadersJsonWithStaticAndProvided()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('getHeaders')->times(1)->with()->andReturn(['Accept-Content' => '$content']);
        $annotationProvider->shouldReceive('getStaticHeaders')->times(1)->with()->andReturn(['foo' => 'bar']);
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    private function assert(MockInterface $annotationProvider, $method)
    {
        $context = $this->getHandlerContext($annotationProvider);

        $handler = new HeaderHandler();
        $handler($context);

        $this->assertResponse($method);
    }
}

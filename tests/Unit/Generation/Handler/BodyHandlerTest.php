<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Handler;

use Mockery;
use Mockery\MockInterface;
use Tebru\Retrofit\Generation\Handler\BodyHandler;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;

/**
 * Class BodyHandlerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyHandlerTest extends AbstractHandlerTest
{
    public function testBodyNoBody()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyStringOptional()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$body');
        $annotationProvider->shouldReceive('isBodyArray')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyStringNotOptional()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(1)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isBodyArray')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyStringNotOptionalDifferentName()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(1)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isBodyArray')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyJsonEncodedArray()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyJsonEncodedParts()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBodyParts')->times(3)->with()->andReturn(['fooPart' => '$fooPart']);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyJsonEncodedObjectSerializable()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(true);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyJsonEncodedObjectNotSerializableEmptyContext()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getSerializationContext')->times(1)->with()->andReturn([]);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyJsonEncodedObjectNotSerializableCompleteContext()
    {
        $context = [
            'groups' => ['group1', 'group2'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => ['foo' => 'bar'],
        ];
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getSerializationContext')->times(1)->with()->andReturn($context);

        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyFormEncodedArray()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(true);


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyFormEncodedParts()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBodyParts')->times(3)->with()->andReturn(['fooPart' => '$fooPart']);


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyFormEncodedObjectSerializable()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(true);


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyFormEncodedObjectNotSerializableNoContext()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getSerializationContext')->times(1)->with()->andReturn([]);


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyFormEncodedObjectNotSerializableCompleteContext()
    {
        $context = [
            'groups' => ['group1', 'group2'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => ['foo' => 'bar'],
        ];
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getSerializationContext')->times(1)->with()->andReturn($context);


        $this->assert($annotationProvider, __FUNCTION__);
    }
    
    // multipart
    public function testBodyMultipartEncodedArray()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getMultipartBoundary')->times(1)->with()->andReturn('fooboundary');


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyMultipartEncodedParts()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBodyParts')->times(3)->with()->andReturn(['fooPart' => '$fooPart']);
        $annotationProvider->shouldReceive('getMultipartBoundary')->times(1)->with()->andReturn('fooboundary');


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyMultipartEncodedObjectSerializable()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getMultipartBoundary')->times(1)->with()->andReturn('fooboundary');


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyMultipartEncodedObjectNotSerializableNoContext()
    {
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getSerializationContext')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('getMultipartBoundary')->times(1)->with()->andReturn('fooboundary');


        $this->assert($annotationProvider, __FUNCTION__);
    }

    public function testBodyMultipartEncodedObjectNotSerializableCompleteContext()
    {
        $context = [
            'groups' => ['group1', 'group2'],
            'version' => 1,
            'serializeNull' => true,
            'enableMaxDepthChecks' => true,
            'attributes' => ['foo' => 'bar'],
        ];
        $annotationProvider = Mockery::mock(AnnotationProvider::class);
        $annotationProvider->shouldReceive('hasBody')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('isBodyOptional')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getBody')->times(2)->with()->andReturn('$retrofitBody');
        $annotationProvider->shouldReceive('isJsonEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isFormUrlEncoded')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyArray')->times(2)->with()->andReturn(false);
        $annotationProvider->shouldReceive('isBodyObject')->times(1)->with()->andReturn(true);
        $annotationProvider->shouldReceive('getBodyParts')->times(1)->with()->andReturn(null);
        $annotationProvider->shouldReceive('isBodyJsonSerializable')->times(1)->with()->andReturn(false);
        $annotationProvider->shouldReceive('getSerializationContext')->times(1)->with()->andReturn($context);
        $annotationProvider->shouldReceive('getMultipartBoundary')->times(1)->with()->andReturn('fooboundary');


        $this->assert($annotationProvider, __FUNCTION__);
    }

    private function assert(MockInterface $annotationProvider, $method)
    {
        $context = $this->getHandlerContext($annotationProvider);

        $handler = new BodyHandler();
        $handler($context);

        $this->assertResponse($method);
    }
}

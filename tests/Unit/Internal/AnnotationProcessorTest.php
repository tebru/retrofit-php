<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Unit\Internal;

use LogicException;
use ReflectionMethod;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\Path;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Internal\AnnotationHandler\BodyAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\HeadersAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\QueryAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationProcessor;
use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\Internal\Converter\DefaultConverterFactory;
use Tebru\Retrofit\Internal\Converter\DefaultRequestBodyConverter;
use Tebru\Retrofit\Internal\ServiceMethod\DefaultServiceMethodBuilder;
use Tebru\Retrofit\Internal\Converter\DefaultStringConverter;
use Tebru\Retrofit\Internal\ParameterHandler\BodyParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\QueryParamHandler;
use Tebru\Retrofit\ServiceMethodBuilder;
use Tebru\Retrofit\Test\LegacyAttributeTestFunctionsTrait;
use Tebru\Retrofit\Test\Mock\Unit\Internal\AnnotationProcessorTest\AnnotationProcessorTestMock;
use Tebru\Retrofit\Test\Mock\Unit\Internal\AnnotationProcessorTest\BadConverterAnnotation;

class AnnotationProcessorTest extends TestCase
{
    use LegacyAttributeTestFunctionsTrait;

    /**
     * @var AnnotationProcessor
     */
    private $annotationProcessor;

    /**
     * @var ServiceMethodBuilder
     */
    private $serviceMethodBuilder;

    /**
     * @var ConverterProvider
     */
    private $converterProvider;

    public function setUp(): void
    {
        $this->annotationProcessor = new AnnotationProcessor([
            Header::class => new HeadersAnnotHandler(),
            Body::class => new BodyAnnotHandler(),
            Query::class => new QueryAnnotHandler(),
            Headers::class => new HeadersAnnotHandler(),
            BadConverterAnnotation::class => new HeadersAnnotHandler(),
        ]);
        $this->serviceMethodBuilder = new DefaultServiceMethodBuilder();
        $this->converterProvider = new ConverterProvider([new DefaultConverterFactory()]);
    }

    public function testParameterAwareAnnotation()
    {
        $this->annotationProcessor->process(
            new Query(['value' => 'bar']),
            $this->serviceMethodBuilder,
            $this->converterProvider,
            new ReflectionMethod(AnnotationProcessorTestMock::class, 'foo')
        );

        self::assertAttributeEquals(
            [new QueryParamHandler(new DefaultStringConverter(), 'bar', false)],
            'parameterHandlers',
            $this->serviceMethodBuilder
        );
    }

    public function testNonParameterAwareAnnotation()
    {
        $this->annotationProcessor->process(
            new Headers(['value' => ['foo:bar']]),
            $this->serviceMethodBuilder,
            $this->converterProvider,
            new ReflectionMethod(AnnotationProcessorTestMock::class, 'foo')
        );

        self::assertAttributeSame(
            ['foo' => ['bar']],
            'headers',
            $this->serviceMethodBuilder
        );
    }

    public function testRequestBodyAnnotation()
    {
        $this->annotationProcessor->process(
            new Body(['value' => 'bar']),
            $this->serviceMethodBuilder,
            $this->converterProvider,
            new ReflectionMethod(AnnotationProcessorTestMock::class, 'body')
        );

        self::assertAttributeEquals(
            [new BodyParamHandler(new DefaultRequestBodyConverter())],
            'parameterHandlers',
            $this->serviceMethodBuilder
        );
    }

    public function testNoHandler()
    {
        $this->annotationProcessor->process(
            new Path(['value' => 'bar']),
            $this->serviceMethodBuilder,
            $this->converterProvider,
            new ReflectionMethod(AnnotationProcessorTestMock::class, 'body')
        );

        self::assertAttributeEquals(
            [],
            'parameterHandlers',
            $this->serviceMethodBuilder
        );
    }

    public function testParameterNotFound()
    {
        try {
            $this->annotationProcessor->process(
                new Query(['value' => 'bar2']),
                $this->serviceMethodBuilder,
                $this->converterProvider,
                new ReflectionMethod(AnnotationProcessorTestMock::class, 'foo')
            );
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Could not find parameter named bar2 in ' .
                'Tebru\Retrofit\Test\Mock\Unit\Internal\AnnotationProcessorTest\AnnotationProcessorTestMock::foo. ' .
                'Please double check that annotations are properly referencing method parameters.',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception was not thrown');
    }

    public function testParameterTypeNotFound()
    {
        try {
            $this->annotationProcessor->process(
                new Query(['value' => 'bar']),
                $this->serviceMethodBuilder,
                $this->converterProvider,
                new ReflectionMethod(AnnotationProcessorTestMock::class, 'noType')
            );
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Parameter type was not found for method ' .
                'Tebru\Retrofit\Test\Mock\Unit\Internal\AnnotationProcessorTest\AnnotationProcessorTestMock::noType',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception was not thrown');
    }

    public function testConverterNotFound()
    {
        try {
            $this->annotationProcessor->process(
                new BadConverterAnnotation(['value' => 'bar']),
                $this->serviceMethodBuilder,
                $this->converterProvider,
                new ReflectionMethod(AnnotationProcessorTestMock::class, 'foo')
            );
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Unable to handle converter of type Foo. Please use RequestBodyConverter or StringConverter',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception was not thrown');
    }
}

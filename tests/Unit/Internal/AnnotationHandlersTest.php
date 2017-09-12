<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Unit\Internal;

use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Field;
use Tebru\Retrofit\Annotation\FieldMap;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\HeaderMap;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\PartMap;
use Tebru\Retrofit\Annotation\Path;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\QueryName;
use Tebru\Retrofit\Annotation\Url;
use Tebru\Retrofit\Internal\AnnotationHandler\BodyAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\FieldAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\FieldMapAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\HeaderAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\HeaderMapAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\HeadersAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\HttpRequestAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\PartAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\PartMapAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\PathAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\QueryAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\QueryMapAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\QueryNameAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\UrlAnnotHandler;
use Tebru\Retrofit\Internal\Converter\DefaultRequestBodyConverter;
use Tebru\Retrofit\Internal\ServiceMethod\DefaultServiceMethodBuilder;
use Tebru\Retrofit\Internal\Converter\DefaultStringConverter;
use Tebru\Retrofit\Internal\ParameterHandler\BodyParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\FieldMapParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\FieldParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\HeaderMapParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\HeaderParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\PartMapParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\PartParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\PathParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\QueryMapParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\QueryNameParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\QueryParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\UrlParamHandler;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ServiceMethodBuilder;
use Tebru\Retrofit\StringConverter;

class AnnotationHandlersTest extends TestCase
{
    /**
     * @var ServiceMethodBuilder
     */
    private $serviceMethodBuilder;

    /**
     * @var RequestBodyConverter
     */
    private $requestBodyConverter;

    /**
     * @var StringConverter
     */
    private $stringConverter;

    public function setUp()
    {
        $this->serviceMethodBuilder = new DefaultServiceMethodBuilder();
        $this->requestBodyConverter = new DefaultRequestBodyConverter();
        $this->stringConverter = new DefaultStringConverter();

    }

    public function testHandleBodyAnnotation()
    {
        (new BodyAnnotHandler())->handle(
            new Body(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->requestBodyConverter,
            1
        );

        self::assertAttributeSame(true, 'hasBody', $this->serviceMethodBuilder);
        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new BodyParamHandler($this->requestBodyConverter)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleFieldAnnotation()
    {
        (new FieldAnnotHandler())->handle(
            new Field(['value' => 'foo', 'encoded' => true]),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeSame(true, 'hasBody', $this->serviceMethodBuilder);
        self::assertAttributeSame('application/x-www-form-urlencoded', 'contentType', $this->serviceMethodBuilder);
        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new FieldParamHandler($this->stringConverter, 'foo', true)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleFieldMapAnnotation()
    {
        (new FieldMapAnnotHandler())->handle(
            new FieldMap(['value' => 'foo', 'encoded' => true]),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeSame(true, 'hasBody', $this->serviceMethodBuilder);
        self::assertAttributeSame('application/x-www-form-urlencoded', 'contentType', $this->serviceMethodBuilder);
        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new FieldMapParamHandler($this->stringConverter, true)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleHeaderAnnotation()
    {
        (new HeaderAnnotHandler())->handle(
            new Header(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new HeaderParamHandler($this->stringConverter, 'foo')], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleHeaderMapAnnotation()
    {
        (new HeaderMapAnnotHandler())->handle(
            new HeaderMap(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new HeaderMapParamHandler($this->stringConverter)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleHeadersAnnotation()
    {
        (new HeadersAnnotHandler())->handle(
            new Headers(['value' => ['Foo: bar', 'Baz: true']]),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeSame(['foo' => ['bar'], 'baz' => ['true']], 'headers', $this->serviceMethodBuilder);
    }

    public function testHandleGETAnnotation()
    {
        (new HttpRequestAnnotHandler())->handle(
            new GET(['value' => '/my/path?q=test']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeSame('GET', 'method', $this->serviceMethodBuilder);
        self::assertAttributeSame('/my/path?q=test', 'path', $this->serviceMethodBuilder);
        self::assertAttributeSame(false, 'hasBody', $this->serviceMethodBuilder);
    }

    public function testHandlePOSTAnnotation()
    {
        (new HttpRequestAnnotHandler())->handle(
            new POST(['value' => '/my/path?q=test']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeSame('POST', 'method', $this->serviceMethodBuilder);
        self::assertAttributeSame('/my/path?q=test', 'path', $this->serviceMethodBuilder);
    }

    public function testHandlePartAnnotation()
    {
        (new PartAnnotHandler())->handle(
            new Part(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->requestBodyConverter,
            1
        );

        self::assertAttributeSame(true, 'hasBody', $this->serviceMethodBuilder);
        self::assertAttributeSame('multipart/form-data', 'contentType', $this->serviceMethodBuilder);
        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new PartParamHandler($this->requestBodyConverter, 'foo', 'binary')], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandlePartMapAnnotation()
    {
        (new PartMapAnnotHandler())->handle(
            new PartMap(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->requestBodyConverter,
            1
        );

        self::assertAttributeSame(true, 'hasBody', $this->serviceMethodBuilder);
        self::assertAttributeSame('multipart/form-data', 'contentType', $this->serviceMethodBuilder);
        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new PartMapParamHandler($this->requestBodyConverter, 'binary')], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandlePathAnnotation()
    {
        (new PathAnnotHandler())->handle(
            new Path(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new PathParamHandler($this->stringConverter, 'foo')], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleQueryAnnotation()
    {
        (new QueryAnnotHandler())->handle(
            new Query(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new QueryParamHandler($this->stringConverter, 'foo', false)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleQueryMapAnnotation()
    {
        (new QueryMapAnnotHandler())->handle(
            new QueryMap(['value' => 'foo', 'encoded' => true]),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new QueryMapParamHandler($this->stringConverter, true)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleQueryNameAnnotation()
    {
        (new QueryNameAnnotHandler())->handle(
            new QueryName(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new QueryNameParamHandler($this->stringConverter, false)], 'parameterHandlers', $this->serviceMethodBuilder);
    }

    public function testHandleUrlAnnotation()
    {
        (new UrlAnnotHandler())->handle(
            new Url(['value' => 'foo']),
            $this->serviceMethodBuilder,
            $this->stringConverter,
            1
        );

        self::assertAttributeCount(1, 'parameterHandlers', $this->serviceMethodBuilder);
        self::assertAttributeEquals([1 => new UrlParamHandler($this->stringConverter)], 'parameterHandlers', $this->serviceMethodBuilder);
    }
}

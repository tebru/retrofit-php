<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Unit\Internal;

use InvalidArgumentException;
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
use Tebru\Retrofit\Test\LegacyAttributeTestFunctionsTrait;

class AnnotationHandlersTest extends TestCase
{
    use LegacyAttributeTestFunctionsTrait;

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

    public function setUp(): void
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

    public function testHandleBodyAnnotationWrongConverter()
    {
        try {
            (new BodyAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a RequestBodyConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleFieldAnnotationWrongAnnotation()
    {
        try {
            (new FieldAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be encodable', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleFieldAnnotationWrongConverter()
    {
        try {
            (new FieldAnnotHandler())->handle(
                new Field(['value' => 'foo', 'encoded' => true]),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleFieldMapAnnotationWrongAnnotation()
    {
        try {
            (new FieldMapAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be encodable', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleFieldMapAnnotationWrongConverter()
    {
        try {
            (new FieldMapAnnotHandler())->handle(
                new FieldMap(['value' => 'foo', 'encoded' => true]),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleHeaderAnnotationWrongConverter()
    {
        try {
            (new HeaderAnnotHandler())->handle(
                new Header(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleHeaderMapAnnotationWrongConverter()
    {
        try {
            (new HeaderMapAnnotHandler())->handle(
                new HeaderMap(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleHeadersAnnotation()
    {
        (new HeadersAnnotHandler())->handle(
            new Headers(['value' => ['Foo: bar', 'Baz: true']]),
            $this->serviceMethodBuilder,
            null,
            1
        );

        self::assertAttributeSame(['foo' => ['bar'], 'baz' => ['true']], 'headers', $this->serviceMethodBuilder);
    }

    public function testHandleHeadersAnnotationWrongConverter()
    {
        try {
            (new HeadersAnnotHandler())->handle(
                new Headers(['value' => ['Foo: bar', 'Baz: true']]),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be null, object found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleGETAnnotation()
    {
        (new HttpRequestAnnotHandler())->handle(
            new GET(['value' => '/my/path?q=test']),
            $this->serviceMethodBuilder,
            null,
            1
        );

        self::assertAttributeSame('GET', 'method', $this->serviceMethodBuilder);
        self::assertAttributeSame('/my/path?q=test', 'path', $this->serviceMethodBuilder);
        self::assertAttributeSame(false, 'hasBody', $this->serviceMethodBuilder);
    }

    public function testHandleGETAnnotationWrongConverter()
    {
        try {
            (new HttpRequestAnnotHandler())->handle(
                new GET(['value' => '/my/path?q=test']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be null, object found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandlePOSTAnnotation()
    {
        (new HttpRequestAnnotHandler())->handle(
            new POST(['value' => '/my/path?q=test']),
            $this->serviceMethodBuilder,
            null,
            1
        );

        self::assertAttributeSame('POST', 'method', $this->serviceMethodBuilder);
        self::assertAttributeSame('/my/path?q=test', 'path', $this->serviceMethodBuilder);
    }

    public function testHandlePOSTAnnotationWrongAnnotation()
    {
        try {
            (new HttpRequestAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be an HttpRequest', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandlePartAnnotationWrongAnnotation()
    {
        try {
            (new PartAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be a Part', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandlePartAnnotationWrongConverter()
    {
        try {
            (new PartAnnotHandler())->handle(
                new Part(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a RequestBodyConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandlePartMapAnnotationWrongAnnotation()
    {
        try {
            (new PartMapAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be a PartMap', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandlePartMapAnnotationWrongConverter()
    {
        try {
            (new PartMapAnnotHandler())->handle(
                new PartMap(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a RequestBodyConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandlePathAnnotationWrongConverter()
    {
        try {
            (new PathAnnotHandler())->handle(
                new Path(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleQueryAnnotationWrongAnnotation()
    {
        try {
            (new QueryAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be encodable', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleQueryAnnotationWrongConverter()
    {
        try {
            (new QueryAnnotHandler())->handle(
                new Query(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleQueryMapAnnotationWrongAnnotation()
    {
        try {
            (new QueryMapAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be encodable', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleQueryMapAnnotationWrongConverter()
    {
        try {
            (new QueryMapAnnotHandler())->handle(
                new QueryMap(['value' => 'foo', 'encoded' => true]),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleQueryNameAnnotationWrongAnnotation()
    {
        try {
            (new QueryNameAnnotHandler())->handle(
                new Body(['value' => 'foo']),
                $this->serviceMethodBuilder,
                $this->stringConverter,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Annotation must be encodable', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testHandleQueryNameAnnotationWrongConverter()
    {
        try {
            (new QueryNameAnnotHandler())->handle(
                new QueryName(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
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

    public function testHandleUrlAnnotationWrongConverter()
    {
        try {
            (new UrlAnnotHandler())->handle(
                new Url(['value' => 'foo']),
                $this->serviceMethodBuilder,
                null,
                1
            );
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Retrofit: Converter must be a StringConverter, NULL found', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }
}

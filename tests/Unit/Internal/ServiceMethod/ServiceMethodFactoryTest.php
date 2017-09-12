<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal\ServiceMethod;

use Doctrine\Common\Annotations\AnnotationReader;
use LogicException;
use Symfony\Component\Cache\Simple\NullCache;
use Tebru\AnnotationReader\AnnotationReaderAdapter;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Internal\AnnotationHandler\BodyAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationHandler\HttpRequestAnnotHandler;
use Tebru\Retrofit\Internal\AnnotationProcessor;
use Tebru\Retrofit\Internal\CallAdapter\CallAdapterProvider;
use Tebru\Retrofit\Internal\CallAdapter\DefaultCallAdapterFactory;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\Internal\Converter\DefaultConverterFactory;
use Tebru\Retrofit\Internal\Converter\DefaultResponseBodyConverter;
use Tebru\Retrofit\Internal\ServiceMethod\ServiceMethodFactory;
use PHPUnit\Framework\TestCase;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ServiceMethod\ServiceMethodFactoryTest\ServiceMethodFactoryTestConverterFactory;
use Tebru\Retrofit\Test\Mock\Unit\Internal\ServiceMethod\ServiceMethodFactoryTest\ServiceMethodFactoryTestClient;

class ServiceMethodFactoryTest extends TestCase
{
    /**
     * @var ServiceMethodFactory
     */
    private $serviceMethodFactory;

    public function setUp()
    {
        $this->serviceMethodFactory = new ServiceMethodFactory(
            new AnnotationProcessor([
                GET::class => new HttpRequestAnnotHandler(),
                Body::class => new BodyAnnotHandler(),
            ]),
            new CallAdapterProvider([new DefaultCallAdapterFactory()]),
            new ConverterProvider([new DefaultConverterFactory(), new ServiceMethodFactoryTestConverterFactory()]),
            new AnnotationReaderAdapter(new AnnotationReader(), new NullCache()),
            'http://example.com'
        );
    }

    public function testCreateServiceMethod()
    {
        $serviceMethod = $this->serviceMethodFactory->create(ServiceMethodFactoryTestClient::class, 'foo');

        self::assertAttributeSame('GET', 'method', $serviceMethod);
        self::assertAttributeSame('http://example.com', 'baseUrl', $serviceMethod);
        self::assertAttributeSame('/foo', 'path', $serviceMethod);
        self::assertAttributeSame([], 'headers', $serviceMethod);
        self::assertAttributeSame([], 'parameterHandlers', $serviceMethod);
        self::assertAttributeEquals(new DefaultResponseBodyConverter(), 'responseBodyConverter', $serviceMethod);
        self::assertAttributeEquals(new DefaultResponseBodyConverter(), 'errorBodyConverter', $serviceMethod);
    }

    public function testCreateServiceMethodCustomConverters()
    {
        $serviceMethod = $this->serviceMethodFactory->create(ServiceMethodFactoryTestClient::class, 'qux');

        self::assertAttributeSame('GET', 'method', $serviceMethod);
        self::assertAttributeSame('http://example.com', 'baseUrl', $serviceMethod);
        self::assertAttributeSame('/', 'path', $serviceMethod);
        self::assertAttributeSame([], 'headers', $serviceMethod);
        self::assertAttributeSame([], 'parameterHandlers', $serviceMethod);
        self::assertAttributeNotEquals(new DefaultResponseBodyConverter(), 'responseBodyConverter', $serviceMethod);
        self::assertAttributeNotEquals(new DefaultResponseBodyConverter(), 'errorBodyConverter', $serviceMethod);
    }

    public function testCreateServiceMethodNoReturnType()
    {
        try {
            $this->serviceMethodFactory->create(ServiceMethodFactoryTestClient::class, 'bar');
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: All service methods must contain a return type. None found for ' .
                'Tebru\Retrofit\Test\Mock\Unit\Internal\ServiceMethod\ServiceMethodFactoryTest\ServiceMethodFactoryTestClient::bar()',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testCreateServiceMethodAnnotationException()
    {
        try {
            $this->serviceMethodFactory->create(ServiceMethodFactoryTestClient::class, 'baz');
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Body cannot be changed after it has been set. This indicates a conflict between HTTP Request ' .
                'annotations, body annotations, and request type annotations. For example, @GET cannot be used with ' .
                '@Body, @Field, or @Part annotations for ' .
                'Tebru\Retrofit\Test\Mock\Unit\Internal\ServiceMethod\ServiceMethodFactoryTest\ServiceMethodFactoryTestClient::baz()',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }
}

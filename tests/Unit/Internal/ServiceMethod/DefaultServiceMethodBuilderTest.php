<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal\ServiceMethod;

use LogicException;
use Tebru\Retrofit\Internal\CallAdapter\DefaultCallAdapter;
use Tebru\Retrofit\Internal\Converter\DefaultRequestBodyConverter;
use Tebru\Retrofit\Internal\Converter\DefaultResponseBodyConverter;
use Tebru\Retrofit\Internal\Converter\DefaultStringConverter;
use Tebru\Retrofit\Internal\ParameterHandler\BodyParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\FieldParamHandler;
use Tebru\Retrofit\Internal\ParameterHandler\PartParamHandler;
use Tebru\Retrofit\Internal\ServiceMethod\DefaultServiceMethodBuilder;
use PHPUnit\Framework\TestCase;

class DefaultServiceMethodBuilderTest extends TestCase
{
    /**
     * @var DefaultServiceMethodBuilder
     */
    private $serviceMethodBuilder;

    public function setUp()
    {
        $this->serviceMethodBuilder = new DefaultServiceMethodBuilder();
    }

    public function testCreateServiceMethodGet()
    {
        $this->serviceMethodBuilder->setMethod('get');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar?q=test');
        $this->serviceMethodBuilder->setCallAdapter(new DefaultCallAdapter());
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setErrorBodyConverter(new DefaultResponseBodyConverter());

        $serviceMethod = $this->serviceMethodBuilder->build();

        self::assertAttributeSame('GET', 'method', $serviceMethod);
        self::assertAttributeSame('http://example.com', 'baseUrl', $serviceMethod);
        self::assertAttributeSame('/foo/bar?q=test', 'path', $serviceMethod);
    }

    public function testCreateServiceMethodPost()
    {
        $paramHandler = new BodyParamHandler(new DefaultRequestBodyConverter());
        $this->serviceMethodBuilder->setMethod('post');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar?q=test');
        $this->serviceMethodBuilder->setIsJson();
        $this->serviceMethodBuilder->addParameterHandler(0, $paramHandler);
        $this->serviceMethodBuilder->setCallAdapter(new DefaultCallAdapter());
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setErrorBodyConverter(new DefaultResponseBodyConverter());

        $serviceMethod = $this->serviceMethodBuilder->build();

        self::assertAttributeSame('POST', 'method', $serviceMethod);
        self::assertAttributeSame('http://example.com', 'baseUrl', $serviceMethod);
        self::assertAttributeSame('/foo/bar?q=test', 'path', $serviceMethod);
        self::assertAttributeSame(['content-type' => ['application/json']], 'headers', $serviceMethod);
        self::assertAttributeSame([$paramHandler], 'parameterHandlers', $serviceMethod);
    }

    public function testCreateServiceMethodForm()
    {
        $paramHandler = new FieldParamHandler(new DefaultStringConverter(), 'foo', false);
        $this->serviceMethodBuilder->setMethod('post');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar?q=test');
        $this->serviceMethodBuilder->setIsFormUrlEncoded();
        $this->serviceMethodBuilder->addParameterHandler(0, $paramHandler);
        $this->serviceMethodBuilder->setCallAdapter(new DefaultCallAdapter());
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setErrorBodyConverter(new DefaultResponseBodyConverter());

        $serviceMethod = $this->serviceMethodBuilder->build();

        self::assertAttributeSame('POST', 'method', $serviceMethod);
        self::assertAttributeSame('http://example.com', 'baseUrl', $serviceMethod);
        self::assertAttributeSame('/foo/bar?q=test', 'path', $serviceMethod);
        self::assertAttributeSame(['content-type' => ['application/x-www-form-urlencoded']], 'headers', $serviceMethod);
        self::assertAttributeSame([$paramHandler], 'parameterHandlers', $serviceMethod);
    }

    public function testCreateServiceMethodMultipart()
    {
        $paramHandler = new PartParamHandler(new DefaultRequestBodyConverter(), 'foo', 'binary');
        $this->serviceMethodBuilder->setMethod('post');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar?q=test');
        $this->serviceMethodBuilder->setIsMultipart();
        $this->serviceMethodBuilder->addParameterHandler(0, $paramHandler);
        $this->serviceMethodBuilder->setCallAdapter(new DefaultCallAdapter());
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setErrorBodyConverter(new DefaultResponseBodyConverter());

        $serviceMethod = $this->serviceMethodBuilder->build();

        self::assertAttributeSame('POST', 'method', $serviceMethod);
        self::assertAttributeSame('http://example.com', 'baseUrl', $serviceMethod);
        self::assertAttributeSame('/foo/bar?q=test', 'path', $serviceMethod);
        self::assertAttributeSame(['content-type' => ['multipart/form-data']], 'headers', $serviceMethod);
        self::assertAttributeSame([$paramHandler], 'parameterHandlers', $serviceMethod);
    }

    public function testSetMethodTwice()
    {
        $this->serviceMethodBuilder->setMethod('get');
        try {
            $this->serviceMethodBuilder->setMethod('post');
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Only one http method is allowed. Trying to set POST, but GET already exists',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testSetHasBodyAfterSet()
    {
        $this->serviceMethodBuilder->setIsJson();
        try {
            $this->serviceMethodBuilder->setHasBody(false);
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Body cannot be changed after it has been set. This indicates a conflict between ' .
                'HTTP Request annotations, body annotations, and request type annotations. For example, ' .
                '@GET cannot be used with @Body, @Field, or @Part annotations',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testChangeContentType()
    {
        $this->serviceMethodBuilder->setIsJson();
        try {
            $this->serviceMethodBuilder->setIsFormUrlEncoded();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Content type cannot be changed after it has been set. This indicates a conflict between ' .
                'HTTP Request annotations, body annotations, and request type annotations. For example, ' .
                '@GET cannot be used with @Body, @Field, or @Part annotations',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutMethod()
    {
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method without HTTP method. Please specify @GET, @POST, etc',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutBaseUrl()
    {
        $this->serviceMethodBuilder->setMethod('GET');
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method without base url. Please specify on RetrofitBuilder',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutPath()
    {
        $this->serviceMethodBuilder->setMethod('GET');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method without HTTP method. Please specify @GET, @POST, etc',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutContentType()
    {
        $this->serviceMethodBuilder->setMethod('POST');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar');
        $this->serviceMethodBuilder->setHasBody(true);
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method with body and no content type. Set one using @Body, ' .
                '@Field, or @Part',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutBody()
    {
        $this->serviceMethodBuilder->setMethod('POST');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar');
        $this->serviceMethodBuilder->setContentType('application/json');
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot set a content-type without a body. This indicates a conflict between ' .
                'HTTP Request annotations, body annotations, and request type annotations. For example, ' .
                '@GET cannot be used with @Body, @Field, or @Part annotations',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutResponseConverter()
    {
        $this->serviceMethodBuilder->setMethod('POST');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar');
        $this->serviceMethodBuilder->setIsJson();
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method without response body converter',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutErrorConverter()
    {
        $this->serviceMethodBuilder->setMethod('POST');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar');
        $this->serviceMethodBuilder->setIsJson();
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method without error body converter',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWithoutCallAdapter()
    {
        $this->serviceMethodBuilder->setMethod('POST');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar');
        $this->serviceMethodBuilder->setIsJson();
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setErrorBodyConverter(new DefaultResponseBodyConverter());
        try {
            $this->serviceMethodBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame(
                'Retrofit: Cannot build service method without call adapter',
                $exception->getMessage()
            );
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testBuildWillNotOverrideContentType()
    {
        $this->serviceMethodBuilder->setMethod('POST');
        $this->serviceMethodBuilder->setBaseUrl('http://example.com');
        $this->serviceMethodBuilder->setPath('/foo/bar');
        $this->serviceMethodBuilder->setHasBody(true);
        $this->serviceMethodBuilder->setContentType('foo');
        $this->serviceMethodBuilder->setResponseBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setErrorBodyConverter(new DefaultResponseBodyConverter());
        $this->serviceMethodBuilder->setCallAdapter(new DefaultCallAdapter());

        $serviceMethod = $this->serviceMethodBuilder->build();

        self::assertAttributeSame(['content-type' => ['foo']], 'headers', $serviceMethod);
    }
}

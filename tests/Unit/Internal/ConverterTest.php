<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Unit\Internal;

use GuzzleHttp\Psr7\AppendStream;
use LogicException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\Internal\Converter\DefaultConverterFactory;
use Tebru\Retrofit\Test\Mock\Unit\MockConverterFactory;

/**
 * Class ConverterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ConverterTest extends TestCase
{
    /**
     * @var ConverterProvider
     */
    private $converterProvider;

    public function setUp(): void
    {
        $this->converterProvider = new ConverterProvider([new DefaultConverterFactory()]);
    }

    public function testRequestBodyConverter()
    {
        $stream = new AppendStream();
        $converted = $this->converterProvider->getRequestBodyConverter(new TypeToken(StreamInterface::class))->convert($stream);

        self::assertSame($stream, $converted);
    }

    public function testRequestBodyConverterProviderCache()
    {
        $converter = $this->converterProvider->getRequestBodyConverter(new TypeToken(StreamInterface::class));
        $converter2 = $this->converterProvider->getRequestBodyConverter(new TypeToken(StreamInterface::class));

        self::assertSame($converter, $converter2);
    }

    public function testRequestBodyConverterProviderException()
    {
        try {
            $this->converterProvider->getRequestBodyConverter(new TypeToken('Foo'));
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Could not get request body converter for type Foo', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }


    public function testResponseBodyConverter()
    {
        $stream = new AppendStream();
        $converted = $this->converterProvider->getResponseBodyConverter(new TypeToken(StreamInterface::class))->convert($stream);

        self::assertSame($stream, $converted);
    }

    public function testResponseBodyConverterProviderCache()
    {
        $converter = $this->converterProvider->getResponseBodyConverter(new TypeToken(StreamInterface::class));
        $converter2 = $this->converterProvider->getResponseBodyConverter(new TypeToken(StreamInterface::class));

        self::assertSame($converter, $converter2);
    }

    public function testResponseBodyConverterProviderException()
    {
        try {
            $this->converterProvider->getResponseBodyConverter(new TypeToken('Foo'));
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Could not get response body converter for type Foo', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testStringConverter()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('string'))->convert('foo');

        self::assertSame('foo', $converted);
    }

    public function testStringConverterInt()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('int'))->convert(1);

        self::assertSame('1', $converted);
    }

    public function testStringConverterFloat()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('float'))->convert(1.5);

        self::assertSame('1.5', $converted);
    }

    public function testStringConverterTrue()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('boolean'))->convert(true);

        self::assertSame('true', $converted);
    }

    public function testStringConverterFalse()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('boolean'))->convert(false);

        self::assertSame('false', $converted);
    }

    public function testStringConverterArray()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('array'))->convert([1]);

        self::assertSame('a:1:{i:0;i:1;}', $converted);
    }

    public function testStringConverterObject()
    {
        $converted = $this->converterProvider->getStringConverter(new TypeToken('object'))->convert(new \stdClass());

        self::assertSame('O:8:"stdClass":0:{}', $converted);
    }

    public function testStringConverterProviderCache()
    {
        $converter = $this->converterProvider->getStringConverter(new TypeToken('string'));
        $converter2 = $this->converterProvider->getStringConverter(new TypeToken('string'));

        self::assertSame($converter, $converter2);
    }

    public function testStringConverterProviderException()
    {
        $converterProvider = new ConverterProvider([new MockConverterFactory()]);
        try {
            $converterProvider->getStringConverter(new TypeToken('Foo'));
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Could not get string converter for type Foo', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }
}

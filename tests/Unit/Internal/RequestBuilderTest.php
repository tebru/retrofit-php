<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal;

use GuzzleHttp\Psr7\AppendStream;
use LogicException;
use Tebru\Retrofit\Internal\RequestBuilder;
use PHPUnit\Framework\TestCase;
use function GuzzleHttp\Psr7\stream_for;

class RequestBuilderTest extends TestCase
{
    public function testSimple()
    {
        $requestBuilder = new RequestBuilder('GET', 'http://example.com', '/test?q=test', []);
        $request = $requestBuilder->build();

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/test?q=test', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
        self::assertSame('', (string)$request->getBody());
    }

    public function testSetBody()
    {
        $stream = new AppendStream();
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/test?q=test', []);
        $requestBuilder->setBody($stream);
        $request = $requestBuilder->build();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('http://example.com/test?q=test', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
        self::assertSame($stream, $request->getBody());
    }

    public function testUrlSetters()
    {
        $requestBuilder = new RequestBuilder('GET', 'http://example.com', '/{part}?q=test', []);
        $requestBuilder->setBaseUrl('https://example2.com');
        $requestBuilder->replacePath('part', 'test');
        $requestBuilder->addQuery('q2', 'test2', false);
        $requestBuilder->addQueryName('contains(foo)', false);
        $request = $requestBuilder->build();

        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://example2.com/test?q2=test2&contains%28foo%29&q=test', (string)$request->getUri());
        self::assertSame(['Host' => ['example2.com']], $request->getHeaders());
        self::assertSame('', (string)$request->getBody());
    }

    public function testUrlSettersEncoded()
    {
        $requestBuilder = new RequestBuilder('GET', 'http://example.com', '/{part}', []);
        $requestBuilder->setBaseUrl('https://example2.com');
        $requestBuilder->replacePath('part', 'test');
        $requestBuilder->addQuery('q2', 'test2', true);
        $requestBuilder->addQueryName('contains%28foo%29', true);
        $request = $requestBuilder->build();

        self::assertSame('GET', $request->getMethod());
        self::assertSame('https://example2.com/test?q2=test2&contains%28foo%29', (string)$request->getUri());
        self::assertSame(['Host' => ['example2.com']], $request->getHeaders());
        self::assertSame('', (string)$request->getBody());
    }

    public function testAddHeader()
    {
        $requestBuilder = new RequestBuilder('GET', 'http://example.com', '/test?q=test', []);
        $requestBuilder->addHeader('foo', 'bar');
        $request = $requestBuilder->build();

        self::assertSame('GET', $request->getMethod());
        self::assertSame('http://example.com/test?q=test', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com'], 'foo' => ['bar']], $request->getHeaders());
        self::assertSame('', (string)$request->getBody());
    }

    public function testFields()
    {
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/', []);
        $requestBuilder->addField('foo', 'bar', false);
        $requestBuilder->addField('foo()', 'bar()', false);
        $request = $requestBuilder->build();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
        self::assertSame('foo=bar&foo%28%29=bar%28%29', (string)$request->getBody());
    }

    public function testFieldsEncoded()
    {
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/', []);
        $requestBuilder->addField('foo', 'bar', true);
        $requestBuilder->addField('foo()', 'bar%28%29', true);
        $request = $requestBuilder->build();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
        self::assertSame('foo=bar&foo%28%29=bar%28%29', (string)$request->getBody());
    }

    public function testParts()
    {
        $stream = stream_for('foo');
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/', []);
        $requestBuilder->addPart('foo', $stream);
        $request = $requestBuilder->build();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
        self::assertNotFalse(strpos((string)$request->getBody(), 'Content-Disposition: form-data; name="foo"'));
        self::assertNotFalse(strpos((string)$request->getBody(), 'Content-Length: 3'));
    }

    public function testPartsFilenameAndHeader()
    {
        $stream = stream_for('foo');
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/', []);
        $requestBuilder->addPart('foo', $stream, ['foo' => 'bar'], 'Test.php');
        $request = $requestBuilder->build();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('http://example.com/', (string)$request->getUri());
        self::assertSame(['Host' => ['example.com']], $request->getHeaders());
        self::assertNotFalse(strpos((string)$request->getBody(), 'foo: bar'));
        self::assertNotFalse(strpos((string)$request->getBody(), 'Content-Disposition: form-data; name="foo"; filename="Test.php"'));
        self::assertNotFalse(strpos((string)$request->getBody(), 'Content-Length: 3'));
    }

    public function testFieldAndBody()
    {
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/test?q=test', []);
        $requestBuilder->setBody(new AppendStream());
        $requestBuilder->addField('foo', 'bar', false);

        try {
            $requestBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Cannot mix @Field and @Body annotations.', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }

    public function testPartAndBody()
    {
        $requestBuilder = new RequestBuilder('POST', 'http://example.com', '/test?q=test', []);
        $requestBuilder->setBody(new AppendStream());
        $requestBuilder->addPart('foo', new AppendStream());

        try {
            $requestBuilder->build();
        } catch (LogicException $exception) {
            self::assertSame('Retrofit: Cannot mix @Part and @Body annotations.', $exception->getMessage());
            return;
        }

        self::fail('Exception not thrown');
    }
}

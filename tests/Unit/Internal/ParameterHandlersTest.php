<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Unit\Unit\Internal;

use ArrayIterator;
use GuzzleHttp\Psr7\AppendStream;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tebru\Retrofit\Http\MultipartBody;
use Tebru\Retrofit\Internal\Converter\DefaultRequestBodyConverter;
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
use Tebru\Retrofit\Internal\RequestBuilder;
use function GuzzleHttp\Psr7\stream_for;

/**
 * Class ParameterHandlersTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ParameterHandlersTest extends TestCase
{
    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    public function setUp()
    {
        $this->requestBuilder = new RequestBuilder('GET', 'http://example.com', '/test/{path}?q=test', []);
    }

    public function testBodyHandler()
    {
        $stream = new AppendStream();
        (new BodyParamHandler(new DefaultRequestBodyConverter()))->apply($this->requestBuilder, $stream);

        self::assertAttributeSame($stream, 'body', $this->requestBuilder);
    }

    public function testBodyHandlerNull()
    {
        (new BodyParamHandler(new DefaultRequestBodyConverter()))->apply($this->requestBuilder, null);

        self::assertAttributeSame(null, 'body', $this->requestBuilder);
    }

    public function testFieldMapHandler()
    {
        $map = [
            'foo' => 'bar',
            'afoo[]' => ['baz', 'qux'],
            'nfoo' => null,
        ];
        $expected = [
            'foo=bar',
            'afoo%5B%5D=baz',
            'afoo%5B%5D=qux',
        ];

        (new FieldMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, $map);

        self::assertAttributeSame($expected, 'fields', $this->requestBuilder);
    }

    public function testFieldMapHandlerIterator()
    {
        $map = new ArrayIterator([
            'foo' => 'bar',
            'afoo[]' => ['baz', 'qux'],
            'nfoo' => null,
        ]);
        $expected = [
            'foo=bar',
            'afoo%5B%5D=baz',
            'afoo%5B%5D=qux',
        ];

        (new FieldMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, $map);

        self::assertAttributeSame($expected, 'fields', $this->requestBuilder);
    }

    public function testFieldMapHandlerEmpty()
    {
        (new FieldMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, []);

        self::assertAttributeSame([], 'fields', $this->requestBuilder);
    }

    public function testFieldMapHandlerNull()
    {
        (new FieldMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'fields', $this->requestBuilder);
    }

    public function testFieldHandler()
    {
        (new FieldParamHandler(new DefaultStringConverter(), 'foo', false))->apply($this->requestBuilder, 'bar');

        self::assertAttributeSame(['foo=bar'], 'fields', $this->requestBuilder);
    }

    public function testFieldHandlerArray()
    {
        (new FieldParamHandler(new DefaultStringConverter(), 'foo', false))->apply($this->requestBuilder, ['bar', 'baz']);

        self::assertAttributeSame(['foo=bar', 'foo=baz'], 'fields', $this->requestBuilder);
    }

    public function testFieldHandlerNull()
    {
        (new FieldParamHandler(new DefaultStringConverter(), 'foo', false))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'fields', $this->requestBuilder);
    }

    public function testHeaderMapHandler()
    {
        $map = [
            'foo' => 'bar',
            'afoo' => ['baz', 'qux'],
            'nfoo' => null,
        ];
        $expected = [
            'foo' => ['bar'],
            'afoo' => ['baz', 'qux'],
        ];

        (new HeaderMapParamHandler(new DefaultStringConverter()))->apply($this->requestBuilder, $map);

        self::assertAttributeSame($expected, 'headers', $this->requestBuilder);
    }

    public function testHeaderMapHandlerIterator()
    {
        $map = new ArrayIterator([
            'foo' => 'bar',
            'afoo' => ['baz', 'qux'],
            'nfoo' => null,
        ]);
        $expected = [
            'foo' => ['bar'],
            'afoo' => ['baz', 'qux'],
        ];

        (new HeaderMapParamHandler(new DefaultStringConverter()))->apply($this->requestBuilder, $map);

        self::assertAttributeSame($expected, 'headers', $this->requestBuilder);
    }

    public function testHeaderMapHandlerEmpty()
    {
        (new HeaderMapParamHandler(new DefaultStringConverter()))->apply($this->requestBuilder, []);

        self::assertAttributeSame([], 'headers', $this->requestBuilder);
    }

    public function testHeaderMapHandlerNull()
    {
        (new HeaderMapParamHandler(new DefaultStringConverter()))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'headers', $this->requestBuilder);
    }

    public function testHeaderHandler()
    {
        (new HeaderParamHandler(new DefaultStringConverter(), 'foo'))->apply($this->requestBuilder, 'bar');

        self::assertAttributeSame(['foo' => ['bar']], 'headers', $this->requestBuilder);
    }

    public function testHeaderHandlerArray()
    {
        (new HeaderParamHandler(new DefaultStringConverter(), 'foo'))->apply($this->requestBuilder, ['bar', 'baz']);

        self::assertAttributeSame(['foo' => ['bar', 'baz']], 'headers', $this->requestBuilder);
    }

    public function testHeaderHandlerNull()
    {
        (new HeaderParamHandler(new DefaultStringConverter(), 'foo'))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'headers', $this->requestBuilder);
    }

    public function testPartMapHandler()
    {
        $stream1 = stream_for('bar');
        $stream2 = stream_for('bar');
        $stream3 = new AppendStream();
        $simpleMultipart = new MultipartBody('mfoo1', $stream2);
        $streamMultipart = new MultipartBody('mfoo2', $stream3, ['a' => 'b'], 'ParameterHandlersTest.php');

        $map = [
            'foo' => $stream1,
            'simple' => $simpleMultipart,
            'stream' => $streamMultipart,
            'nfoo' => null,
        ];

        $expected = [
            [
                'name' => 'foo',
                'contents' => $stream1,
                'headers' => ['Content-Transfer-Encoding' => 'binary'],
                'filename' => null,
            ],
            [
                'name' => 'mfoo1',
                'contents' => $stream2,
                'headers' => ['Content-Transfer-Encoding' => 'binary'],
                'filename' => null,
            ],
            [
                'name' => 'mfoo2',
                'contents' => new $stream3,
                'headers' => ['a' => 'b', 'Content-Transfer-Encoding' => 'binary'],
                'filename' => 'ParameterHandlersTest.php',
            ],
        ];

        (new PartMapParamHandler(new DefaultRequestBodyConverter(), 'binary'))->apply($this->requestBuilder, $map);

        self::assertAttributeEquals($expected, 'parts', $this->requestBuilder);
    }

    public function testPartMapHandlerIterator()
    {
        $stream1 = stream_for('bar');
        $stream2 = stream_for('bar');
        $stream3 = new AppendStream();
        $simpleMultipart = new MultipartBody('mfoo1', $stream2);
        $streamMultipart = new MultipartBody('mfoo2', $stream3, ['a' => 'b'], 'ParameterHandlersTest.php');

        $map = new ArrayIterator([
            'foo' => $stream1,
            'simple' => $simpleMultipart,
            'stream' => $streamMultipart,
            'nfoo' => null,
        ]);

        $expected = [
            [
                'name' => 'foo',
                'contents' => $stream1,
                'headers' => ['Content-Transfer-Encoding' => 'binary'],
                'filename' => null,
            ],
            [
                'name' => 'mfoo1',
                'contents' => $stream2,
                'headers' => ['Content-Transfer-Encoding' => 'binary'],
                'filename' => null,
            ],
            [
                'name' => 'mfoo2',
                'contents' => new $stream3,
                'headers' => ['a' => 'b', 'Content-Transfer-Encoding' => 'binary'],
                'filename' => 'ParameterHandlersTest.php',
            ],
        ];

        (new PartMapParamHandler(new DefaultRequestBodyConverter(), 'binary'))->apply($this->requestBuilder, $map);

        self::assertAttributeEquals($expected, 'parts', $this->requestBuilder);
    }

    public function testPartMapHandlerEmpty()
    {
        (new PartMapParamHandler(new DefaultRequestBodyConverter(), 'binary'))->apply($this->requestBuilder, []);

        self::assertAttributeSame([], 'parts', $this->requestBuilder);
    }

    public function testPartMapHandlerNull()
    {
        (new PartMapParamHandler(new DefaultRequestBodyConverter(), 'binary'))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'parts', $this->requestBuilder);
    }

    public function testPartHandler()
    {
        $stream = stream_for('bar');
        $expected = [[
            'name' => 'foo',
            'contents' => $stream,
            'headers' => ['Content-Transfer-Encoding' => 'binary'],
            'filename' => null,
        ]];
        (new PartParamHandler(new DefaultRequestBodyConverter(), 'foo', 'binary'))->apply($this->requestBuilder, $stream);

        self::assertAttributeSame($expected, 'parts', $this->requestBuilder);
    }

    public function testPartHandlerMultipart()
    {
        $stream = stream_for('bar');
        $multipart = new MultipartBody('foo', $stream);
        $expected = [[
            'name' => 'foo',
            'contents' => $stream,
            'headers' => ['Content-Transfer-Encoding' => 'binary'],
            'filename' => null,
        ]];
        (new PartParamHandler(new DefaultRequestBodyConverter(), 'foo', 'binary'))->apply($this->requestBuilder, $multipart);

        self::assertAttributeSame($expected, 'parts', $this->requestBuilder);
    }

    public function testPartHandlerMultipartHeadersAndFilename()
    {
        $stream = stream_for('bar');
        $multipart = new MultipartBody('foo', $stream, ['a' => 'b'], 'Test.php');
        $expected = [[
            'name' => 'foo',
            'contents' => $stream,
            'headers' => ['a' => 'b', 'Content-Transfer-Encoding' => 'binary'],
            'filename' => 'Test.php',
        ]];
        (new PartParamHandler(new DefaultRequestBodyConverter(), 'foo', 'binary'))->apply($this->requestBuilder, $multipart);

        self::assertAttributeSame($expected, 'parts', $this->requestBuilder);
    }

    public function testPartHandlerNull()
    {
        (new PartParamHandler(new DefaultRequestBodyConverter(), 'foo', 'binary'))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'parts', $this->requestBuilder);
    }

    public function testPathHandler()
    {
        (new PathParamHandler(new DefaultStringConverter(), 'path'))->apply($this->requestBuilder, 'bar');

        self::assertAttributeEquals(new Uri('http://example.com/test/bar?q=test'), 'uri', $this->requestBuilder);
    }

    public function testPathHandlerNull()
    {
        try {
            (new PathParamHandler(new DefaultStringConverter(), 'path'))->apply($this->requestBuilder, null);
        } catch (RuntimeException $exception) {
            self::assertSame('Path parameters cannot be null', $exception->getMessage());
            return;
        }

        self::fail('Exception was not thrown');
    }

    public function testQueryMapHandler()
    {
        $map = [
            'foo' => 'bar',
            'afoo[]' => ['baz', 'qux'],
            'nfoo' => null,
        ];
        $expected = [
            'foo=bar',
            'afoo%5B%5D=baz',
            'afoo%5B%5D=qux',
        ];
        (new QueryMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, $map);

        self::assertAttributeSame($expected, 'queries', $this->requestBuilder);
    }

    public function testQueryMapHandlerIterator()
    {
        $map = new ArrayIterator([
            'foo' => 'bar',
            'afoo[]' => ['baz', 'qux'],
            'nfoo' => null,
        ]);
        $expected = [
            'foo=bar',
            'afoo%5B%5D=baz',
            'afoo%5B%5D=qux',
        ];
        (new QueryMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, $map);

        self::assertAttributeSame($expected, 'queries', $this->requestBuilder);
    }

    public function testQueryMapHandlerEmpty()
    {
        (new QueryMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, []);

        self::assertAttributeSame([], 'queries', $this->requestBuilder);
    }

    public function testQueryMapHandlerNull()
    {
        (new QueryMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'queries', $this->requestBuilder);
    }

    public function testQueryNameHandler()
    {
        (new QueryNameParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, 'bar');

        self::assertAttributeSame(['bar'], 'queries', $this->requestBuilder);
    }

    public function testQueryNameHandlerArray()
    {
        (new QueryNameParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, ['bar', 'baz']);

        self::assertAttributeSame(['bar', 'baz'], 'queries', $this->requestBuilder);
    }

    public function testQueryNameHandlerNull()
    {
        (new QueryNameParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'queries', $this->requestBuilder);
    }

    public function testQueryHandler()
    {
        (new QueryParamHandler(new DefaultStringConverter(), 'foo', false))->apply($this->requestBuilder, 'bar');

        self::assertAttributeSame(['foo=bar'], 'queries', $this->requestBuilder);
    }

    public function testQueryHandlerArray()
    {
        (new QueryParamHandler(new DefaultStringConverter(), 'foo', false))->apply($this->requestBuilder, ['bar', 'baz']);

        self::assertAttributeSame(['foo=bar', 'foo=baz'], 'queries', $this->requestBuilder);
    }

    public function testQueryHandlerNull()
    {
        (new QueryParamHandler(new DefaultStringConverter(), 'foo', false))->apply($this->requestBuilder, null);

        self::assertAttributeSame([], 'queries', $this->requestBuilder);
    }

    public function testUrlHandler()
    {
        (new UrlParamHandler(new DefaultStringConverter()))->apply($this->requestBuilder, 'http://example2.com');

        self::assertAttributeEquals(new Uri('http://example2.com/test/{path}?q=test'), 'uri', $this->requestBuilder);
    }

    public function testUrlHandlerNull()
    {
        try {
            (new UrlParamHandler(new DefaultStringConverter()))->apply($this->requestBuilder, null);
        } catch (RuntimeException $exception) {
            self::assertSame('Url parameters cannot be null', $exception->getMessage());
            return;
        }

        self::fail('Exception was not thrown');
    }

    public function testUsingMapAsListThrowsException()
    {
        $map = ['foo' => ['foo' => 'bar']];

        try {
            (new FieldMapParamHandler(new DefaultStringConverter(), false))->apply($this->requestBuilder, $map);
        } catch (RunTimeException $exception) {
            self::assertSame('Retrofit: Array value must use numeric keys', $exception->getMessage());
            return;
        }

        self::fail('Exception was not thrown');
    }
}

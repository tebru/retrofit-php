<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\DELETE;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HEAD;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\OPTIONS;
use Tebru\Retrofit\Annotation\PATCH;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\PUT;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class HttpRequestTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HttpRequestTest extends MockeryTestCase
{
    /**
     * @dataProvider provideHttpRequestAnnotations
     * @expectedException \OutOfBoundsException
     */
    public function testBlankHttpRequestThrowsException($class)
    {
        /** @var HttpRequest $annotation */
        $annotation = new $class([]);

        $this->assertEquals('', $annotation->getPath());
        $this->assertEquals([], $annotation->getParameters());
        $this->assertEquals([], $annotation->getQueries());
    }

    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testHttpRequestSimplePath($class)
    {
        /** @var HttpRequest $annotation */
        $annotation = new $class(['value' => '/path']);

        $this->assertEquals('/path', $annotation->getPath());
        $this->assertEquals([], $annotation->getParameters());
        $this->assertEquals([], $annotation->getQueries());
    }

    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testHttpRequestPathWithParameter($class)
    {
        /** @var HttpRequest $annotation */
        $annotation = new $class(['value' => '/path/{id}']);

        $this->assertEquals('/path/$id', $annotation->getPath());
        $this->assertEquals(['id'], $annotation->getParameters());
        $this->assertEquals([], $annotation->getQueries());
    }

    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testHttpRequestPathWithMultipleParameters($class)
    {
        /** @var HttpRequest $annotation */
        $annotation = new $class(['value' => '/path/{id}{id2}']);

        $this->assertEquals('/path/$id$id2', $annotation->getPath());
        $this->assertEquals(['id', 'id2'], $annotation->getParameters());
        $this->assertEquals([], $annotation->getQueries());
    }

    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testHttpRequestPathWithQuery($class)
    {
        /** @var HttpRequest $annotation */
        $annotation = new $class(['value' => '/path?foo=bar']);

        $this->assertEquals('/path', $annotation->getPath());
        $this->assertEquals([], $annotation->getParameters());
        $this->assertEquals(['foo' => 'bar'], $annotation->getQueries());
    }

    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testHttpRequestPathWithMultipleQuery($class)
    {
        /** @var HttpRequest $annotation */
        $annotation = new $class(['value' => '/path?foo=bar&baz=boing']);

        $this->assertEquals('/path', $annotation->getPath());
        $this->assertEquals([], $annotation->getParameters());
        $this->assertEquals(['foo' => 'bar', 'baz' => 'boing'], $annotation->getQueries());
    }

    public function testGetType()
    {
        $annotation = new GET(['value' => '/path']);

        $this->assertEquals('get', $annotation->getType());
    }

    public function testPostType()
    {
        $annotation = new POST(['value' => '/path']);

        $this->assertEquals('post', $annotation->getType());
    }

    public function testPutType()
    {
        $annotation = new PUT(['value' => '/path']);

        $this->assertEquals('put', $annotation->getType());
    }

    public function testPatchType()
    {
        $annotation = new PATCH(['value' => '/path']);

        $this->assertEquals('patch', $annotation->getType());
    }

    public function testDeleteType()
    {
        $annotation = new DELETE(['value' => '/path']);

        $this->assertEquals('delete', $annotation->getType());
    }

    public function testHeadType()
    {
        $annotation = new HEAD(['value' => '/path']);

        $this->assertEquals('head', $annotation->getType());
    }

    public function testOptionsType()
    {
        $annotation = new OPTIONS(['value' => '/path']);

        $this->assertEquals('options', $annotation->getType());
    }

    public function provideHttpRequestAnnotations()
    {
        return [
            [GET::class],
            [POST::class],
            [PUT::class],
            [PATCH::class],
            [DELETE::class],
            [HEAD::class],
            [OPTIONS::class],
        ];
    }
}

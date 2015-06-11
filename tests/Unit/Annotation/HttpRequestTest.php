<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\DELETE;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HEAD;
use Tebru\Retrofit\Annotation\HttpRequest;
use Tebru\Retrofit\Annotation\OPTIONS;
use Tebru\Retrofit\Annotation\PATCH;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\PUT;

/**
 * Class HttpRequestTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HttpRequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideHttpRequestAnnotations
     */
    public function testBlankHttpRequest($class)
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
        $annotation = new GET([]);

        $this->assertEquals('get', $annotation->getType());
    }

    public function testPostType()
    {
        $annotation = new POST([]);

        $this->assertEquals('post', $annotation->getType());
    }

    public function testPutType()
    {
        $annotation = new PUT([]);

        $this->assertEquals('put', $annotation->getType());
    }

    public function testPatchType()
    {
        $annotation = new PATCH([]);

        $this->assertEquals('patch', $annotation->getType());
    }

    public function testDeleteType()
    {
        $annotation = new DELETE([]);

        $this->assertEquals('delete', $annotation->getType());
    }

    public function testHeadType()
    {
        $annotation = new HEAD([]);

        $this->assertEquals('head', $annotation->getType());
    }

    public function testOptionsType()
    {
        $annotation = new OPTIONS([]);

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

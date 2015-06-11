<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\Headers;

/**
 * Class HeadersTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeadersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Tebru\Retrofit\Exception\AnnotationConditionMissingException
     */
    public function testConstructorThrowsException()
    {
        new Headers([]);
    }

    public function testOneHeader()
    {
        $annotation = new Headers(['value' => 'Foo: bar']);

        $this->assertEquals(['Foo' => 'bar'], $annotation->getHeaders());
    }

    public function testMultipleHeader()
    {
        $annotation = new Headers(['value' => ['Foo: bar', 'Baz: boing']]);

        $this->assertEquals(['Foo' => 'bar', 'Baz' => 'boing'], $annotation->getHeaders());
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\AnnotationConditionMissingException
     */
    public function testBadlyFormattedHeaderThrowsException()
    {
        new Headers(['value' => 'Foo bar']);
    }
}

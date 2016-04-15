<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class HeadersTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeadersTest extends MockeryTestCase
{
    /**
     * @expectedException \OutOfBoundsException
     * @expectedExceptionMessage An argument was not passed to a "Tebru\Retrofit\Annotation\Headers" annotation.
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
     * @expectedException \LogicException
     * @expectedExceptionMessage Header in an incorrect format.  Expected "Name: value"
     */
    public function testBadlyFormattedHeaderThrowsException()
    {
        new Headers(['value' => 'Foo bar']);
    }
}

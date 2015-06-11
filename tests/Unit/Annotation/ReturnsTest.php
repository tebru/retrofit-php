<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use OutOfRangeException;
use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\Returns;

/**
 * Class ReturnsTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException OutOfRangeException
     */
    public function testConstructorThrowsException()
    {
        new Returns([]);
    }

    public function testSimple()
    {
        $annotation = new Returns(['value' => 'test']);

        $this->assertEquals('test', $annotation->getReturn());
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ReturnsTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnsTest extends MockeryTestCase
{
    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage An argument was not passed to a "Tebru\Retrofit\Annotation\Returns" annotation.
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

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Manipulator;

use Tebru\Retrofit\Generation\Manipulator\BodyManipulator;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BodyManipulatorTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyManipulatorTest extends MockeryTestCase
{
    public function testBoolToString()
    {
        $array = [
            'key1',
            true,
            false,
            'key2' => true,
            'key3' => false,
            'key4' => [
                true,
                false,
                'key1' => true,
                'key2' => false,
            ],
        ];
        $expected = [
            'key1',
            'true',
            'false',
            'key2' => 'true',
            'key3' => 'false',
            'key4' => [
                'true',
                'false',
                'key1' => 'true',
                'key2' => 'false',
            ],
        ];

        $result = BodyManipulator::boolToString($array);

        $this->assertSame($expected, $result);
    }

    public function testVarToStringWithNull()
    {
        $this->assertSame('null', BodyManipulator::varToString(null));
    }

    public function testVarToStringWithTrue()
    {
        $this->assertSame('true', BodyManipulator::varToString(true));
    }

    public function testVarToStringWithFalse()
    {
        $this->assertSame('false', BodyManipulator::varToString(false));
    }

    public function testVarToStringWithArray()
    {
        $this->assertSame('[]', BodyManipulator::varToString([]));
    }

    public function testVarToStringWithInt()
    {
        $this->assertSame('1', BodyManipulator::varToString(1));
    }

    public function testVarToStringWithString()
    {
        $this->assertSame('string', BodyManipulator::varToString('string'));
    }
}

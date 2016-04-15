<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Manipulator;

use Tebru\Retrofit\Generation\Manipulator\QueryManipulator;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class QueryManipulatorTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class QueryManipulatorTest extends MockeryTestCase
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

        $result = QueryManipulator::boolToString($array);

        $this->assertSame($expected, $result);
    }

    public function testVarToStringWithNull()
    {
        $this->assertSame('null', QueryManipulator::varToString(null));
    }

    public function testVarToStringWithTrue()
    {
        $this->assertSame('true', QueryManipulator::varToString(true));
    }

    public function testVarToStringWithFalse()
    {
        $this->assertSame('false', QueryManipulator::varToString(false));
    }

    public function testVarToStringWithArray()
    {
        $this->assertSame('[]', QueryManipulator::varToString([]));
    }

    public function testVarToStringWithInt()
    {
        $this->assertSame('1', QueryManipulator::varToString(1));
    }

    public function testVarToStringWithString()
    {
        $this->assertSame('string', QueryManipulator::varToString('string'));
    }
}

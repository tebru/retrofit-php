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
}

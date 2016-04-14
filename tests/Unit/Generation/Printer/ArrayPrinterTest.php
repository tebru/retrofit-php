<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Printer;

use Tebru\Retrofit\Generation\Printer\ArrayPrinter;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ArrayPrinterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ArrayPrinterTest extends MockeryTestCase
{
    public function testArrayPrint()
    {
        $printer = new ArrayPrinter();
        $result = $printer->printArray([
            0, 1, 2, 0.5, 1.5, 2.5, 'a', 'b', 'c', true, false, null, '$foo',
            [0, 1, 2, 0.5, 1.5, 2.5, 'a', 'b', 'c', true, false, null, '$foo'],
            'a' => 0, 'b' => 1, 'c' => 2, 'd' => 0.5, 'e' => 1.5, 'f' => 2.5, 'g' => 'a', 'h' => 'b', 'i' => 'c', 'j' => true, 'k' => false, 'l' => null, 'm' => '$foo',
            'n' => ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 0.5, 'e' => 1.5, 'f' => 2.5, 'g' => 'a', 'h' => 'b', 'i' => 'c', 'j' => true, 'k' => false, 'l' => null, 'm' => '$foo'],
        ]);

        // remove newlines
        $result = str_replace("\n ", '', $result);
        $result = str_replace("\n", '', $result);

        $this->assertSame("array ( 0 => 0, 1 => 1, 2 => 2, 3 => 0.5, 4 => 1.5, 5 => 2.5, 6 => 'a', 7 => 'b', 8 => 'c', 9 => true, 10 => false, 11 => NULL, 12 => \$foo, 13 =>  array (   0 => 0,   1 => 1,   2 => 2,   3 => 0.5,   4 => 1.5,   5 => 2.5,   6 => 'a',   7 => 'b',   8 => 'c',   9 => true,   10 => false,   11 => NULL,   12 => \$foo, ), 'a' => 0, 'b' => 1, 'c' => 2, 'd' => 0.5, 'e' => 1.5, 'f' => 2.5, 'g' => 'a', 'h' => 'b', 'i' => 'c', 'j' => true, 'k' => false, 'l' => NULL, 'm' => \$foo, 'n' =>  array (   'a' => 0,   'b' => 1,   'c' => 2,   'd' => 0.5,   'e' => 1.5,   'f' => 2.5,   'g' => 'a',   'h' => 'b',   'i' => 'c',   'j' => true,   'k' => false,   'l' => NULL,   'm' => \$foo, ),)", $result);
    }
}

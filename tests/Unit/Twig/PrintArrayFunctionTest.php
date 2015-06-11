<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Twig;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Twig\PrintArrayFunction;

/**
 * Class PrintArrayFunctionTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class PrintArrayFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testPrintingArray()
    {
        $array = ['key' => 'value', 'key2' => ['subkey' => 'subvalue'], '$test'];
    
        $printArrayFunction = new PrintArrayFunction();
        $result = $printArrayFunction($array);

        $this->assertEquals("['key' => 'value', 'key2' => ['subkey' => 'subvalue'], \$test]", $result);
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test;

use Mockery;
use PHPUnit_Framework_Error_Deprecated;
use PHPUnit_Framework_TestCase;

/**
 * Class MockeryTestCase
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MockeryTestCase extends PHPUnit_Framework_TestCase
{
    private $errorLevel;

    protected function setUp()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $this->errorLevel = error_reporting();
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    protected function disableDeprecationWarning()
    {
        PHPUnit_Framework_Error_Deprecated::$enabled = false;
        error_reporting(0);
    }

    protected function enableDeprecationWarning()
    {
        PHPUnit_Framework_Error_Deprecated::$enabled = true;
        error_reporting($this->errorLevel);
    }
}

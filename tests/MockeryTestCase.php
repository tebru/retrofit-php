<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test;

use Mockery;
use PHPUnit_Framework_TestCase;

/**
 * Class MockeryTestCase
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MockeryTestCase extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
    }

    protected function tearDown()
    {
        Mockery::close();
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use Tebru\Retrofit\Event\ReturnEvent;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ReturnEventTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnEventTest extends MockeryTestCase
{
    public function testGetters()
    {
        $event = new ReturnEvent('return');
        $this->assertSame('return', $event->getReturn());
    }

    public function testSetters()
    {
        $event = new ReturnEvent('return');
        $event->setReturn('return2');
        $this->assertSame('return2', $event->getReturn());
    }
}

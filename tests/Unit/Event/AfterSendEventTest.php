<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use Tebru\Retrofit\Adapter\Http\Response;
use Tebru\Retrofit\Event\AfterSendEvent;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class AfterSendEventTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AfterSendEventTest extends MockeryTestCase
{
    public function testGetters()
    {
        $event = new AfterSendEvent(new Response('body'));
        $this->assertInstanceOf(Response::class, $event->getResponse());
    }
}

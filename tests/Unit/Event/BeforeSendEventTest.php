<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use Tebru\Retrofit\Event\BeforeSendEvent;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BeforeSendEventTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BeforeSendEventTest extends MockeryTestCase
{
    public function testGetters()
    {
        $event = new BeforeSendEvent('method', 'requestUrl', 'headers', 'body');
        $this->assertSame('method', $event->getMethod());
        $this->assertSame('requestUrl', $event->getRequestUrl());
        $this->assertSame('headers', $event->getHeaders());
        $this->assertSame('body', $event->getBody());
    }
}

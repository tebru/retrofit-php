<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use GuzzleHttp\Psr7\Request;
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
        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $this->assertInstanceOf(Request::class, $event->getRequest());
        $this->assertSame('POST', $event->getMethod());
        $this->assertSame('http://mockservice.com/post', $event->getRequestUrl());
        $this->assertSame(['Host' => ['mockservice.com'], 'foo' => ['bar']], $event->getHeaders());
        $this->assertSame('body', $event->getBody());
    }
}

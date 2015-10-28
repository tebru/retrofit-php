<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
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
        $event = new AfterSendEvent(new Request('GET', 'http://mockservice.com/get'), new Response(200, [], 'body'));
        $this->assertInstanceOf(Response::class, $event->getResponse());
        $this->assertInstanceOf(Request::class, $event->getRequest());
    }

    public function testSetters()
    {
        $response = new Response(200, [], 'body');
        $event = new AfterSendEvent(new Request('GET', 'http://mockservice.com/get'), $response);
        $response = $response->withStatus(500);
        $event->setResponse($response);
        $this->assertSame(500, $event->getResponse()->getStatusCode());
    }
}

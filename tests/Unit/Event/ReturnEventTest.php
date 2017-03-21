<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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
        $event = new ReturnEvent('return', new Request('GET', 'http://mockservice.com/get'), new Response());
        $this->assertSame('return', $event->getReturn());
        $this->assertInstanceOf(RequestInterface::class, $event->getRequest());
        $this->assertInstanceOf(ResponseInterface::class, $event->getResponse());
    }

    public function testSetters()
    {
        $event = new ReturnEvent('return', new Request('GET', 'http://mockservice.com/get'), new Response());
        $event->setReturn('return2');
        $this->assertSame('return2', $event->getReturn());
    }
}

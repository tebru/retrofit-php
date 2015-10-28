<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Event;

use Exception;
use GuzzleHttp\Psr7\Request;
use InvalidArgumentException;
use Tebru\Retrofit\Event\ApiExceptionEvent;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ApiExceptionEventTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ApiExceptionEventTest extends MockeryTestCase
{
    public function testGetters()
    {
        $event = new ApiExceptionEvent(new Exception(), new Request('GET', 'http://mockservice.com/get'));
        $this->assertInstanceOf(Exception::class, $event->getException());
        $this->assertInstanceOf(Request::class, $event->getRequest());
    }

    public function testSetters()
    {
        $event = new ApiExceptionEvent(new Exception());
        $event->setException(new InvalidArgumentException());
        $this->assertInstanceOf(InvalidArgumentException::class, $event->getException());
    }
}

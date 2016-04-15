<?php
/*
 * Copyright (c) Nate Brunette.
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
    public function testRequest()
    {
        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $this->assertInstanceOf(Request::class, $event->getRequest());
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage Retrofit Deprecation: This method is deprecated, use getRequest() instead
     */
    public function testGetMethodIsDeprecated()
    {
        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $event->getMethod();
    }

    public function testGetMethod()
    {
        $this->disableDeprecationWarning();

        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $this->assertSame('POST', $event->getMethod());

        $this->enableDeprecationWarning();
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage Retrofit Deprecation: This method is deprecated, use getRequest() instead
     */
    public function testGetRequestUrlIsDeprecated()
    {
        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $event->getRequestUrl();
    }

    public function testGetRequestUrl()
    {
        $this->disableDeprecationWarning();

        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $this->assertSame('http://mockservice.com/post', $event->getRequestUrl());

        $this->enableDeprecationWarning();
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage Retrofit Deprecation: This method is deprecated, use getRequest() instead
     */
    public function testGetHeadersIsDeprecated()
    {
        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $event->getHeaders();
    }

    public function testGetHeaders()
    {
        $this->disableDeprecationWarning();

        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $this->assertSame(['Host' => ['mockservice.com'], 'foo' => ['bar']], $event->getHeaders());

        $this->enableDeprecationWarning();
    }

    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     * @expectedExceptionMessage Retrofit Deprecation: This method is deprecated, use getRequest() instead
     */
    public function testGetBodyIsDeprecated()
    {
        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $event->getBody();
    }

    public function testGetBody()
    {
        $this->disableDeprecationWarning();

        $event = new BeforeSendEvent(new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body'));
        $this->assertSame('body', $event->getBody());

        $this->enableDeprecationWarning();
    }

    public function testSetters()
    {
        $request = new Request('POST', 'http://mockservice.com/post', ['foo' => 'bar'], 'body');
        $event = new BeforeSendEvent($request);
        $request = $request->withMethod('PUT');
        $event->setRequest($request);
        $this->assertEquals('PUT', $event->getRequest()->getMethod());
    }

}

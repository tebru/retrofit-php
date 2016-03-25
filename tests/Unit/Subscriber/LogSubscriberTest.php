<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Subscriber;

use Exception;
use Mockery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Tebru\Retrofit\Event\AfterSendEvent;
use Tebru\Retrofit\Event\ApiExceptionEvent;
use Tebru\Retrofit\Subscriber\LogSubscriber;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class LogSubscriberTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class LogSubscriberTest extends MockeryTestCase
{
    public function testGetSubscribedEvents()
    {
        $expectedEvents = [
            AfterSendEvent::NAME => 'onAfterSend',
            ApiExceptionEvent::NAME => 'onApiException',
        ];

        $this->assertSame($expectedEvents, LogSubscriber::getSubscribedEvents());
    }

    public function testAfterSend()
    {
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('getMethod')->times(1)->with()->andReturn('GET');
        $request->shouldReceive('getUri')->times(1)->with()->andReturn('http://example.com/get');
        $request->shouldReceive('getHeaders')->times(1)->with()->andReturn([]);
        $request->shouldReceive('getBody')->times(1)->with()->andReturn('[]');

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->times(1)->with()->andReturn(200);
        $response->shouldReceive('getReasonPhrase')->times(1)->with()->andReturn('OK');
        $response->shouldReceive('getBody')->times(1)->with()->andReturn('[]');
        $response->shouldReceive('getHeaders')->times(1)->with()->andReturn([]);

        $log = [
            'request' => [
                'method' => 'GET',
                'uri' => 'http://example.com/get',
                'headers' => [],
                'body' => '[]',
            ],
            'response' => [
                'statusCode' => 200,
                'reasonPhrase' => 'OK',
                'body' => '[]',
                'headers' => [],
            ],
        ];

        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('debug')->times(1)->with('Sent Request', $log)->andReturn();

        $event = Mockery::mock(AfterSendEvent::class);
        $event->shouldReceive('getRequest')->times(1)->with()->andReturn($request);
        $event->shouldReceive('getResponse')->times(1)->with()->andReturn($response);

        $subscriber = new LogSubscriber($logger);
        $subscriber->onAfterSend($event);
    }

    public function testException()
    {
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('getMethod')->times(1)->with()->andReturn('GET');
        $request->shouldReceive('getUri')->times(1)->with()->andReturn('http://example.com/get');
        $request->shouldReceive('getHeaders')->times(1)->with()->andReturn([]);
        $request->shouldReceive('getBody')->times(1)->with()->andReturn('[]');

        $exception = Mockery::mock(Exception::class);

        $log = [
            'request' => [
                'method' => 'GET',
                'uri' => 'http://example.com/get',
                'headers' => [],
                'body' => '[]',
            ],
            'exception' => $exception,
        ];

        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('error')->times(1)->with('Request Exception', $log)->andReturn();

        $event = Mockery::mock(ApiExceptionEvent::class);
        $event->shouldReceive('getRequest')->times(1)->with()->andReturn($request);
        $event->shouldReceive('getException')->times(1)->with()->andReturn($exception);

        $subscriber = new LogSubscriber($logger);
        $subscriber->onApiException($event);
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Rest;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use Mockery;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapterBuilder;
use Tebru\Retrofit\HttpClient\Adapter\Guzzle\GuzzleV6ClientAdapter;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RestAdapterBuilderTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestAdapterBuilderTest extends MockeryTestCase
{
    public function testSimple()
    {
        $restAdapter = $this->getRestAdapterBuilder()->build();

        $this->assertTrue($restAdapter instanceof RestAdapter);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     */
    public function testNoBaseUrlThrowsException()
    {
        RestAdapter::builder()->build();
    }

    public function testSetHttpClient()
    {
        $client = new Client();
        $restAdapter = $this->getRestAdapterBuilder()->setHttpClient($client)->build();

        $this->assertAttributeInstanceOf(GuzzleV6ClientAdapter::class, 'httpClient', $restAdapter);
    }

    public function testSetClientAdapter()
    {
        $client = new Client();
        $clientAdapter = new GuzzleV6ClientAdapter($client);
        $restAdapter = $this->getRestAdapterBuilder()->setClientAdapter($clientAdapter)->build();

        $this->assertAttributeInstanceOf(GuzzleV6ClientAdapter::class, 'httpClient', $restAdapter);
    }

    public function testSetSerializer()
    {
        $serializer = SerializerBuilder::create()->build();
        $restAdapter = $this->getRestAdapterBuilder()->setSerializer($serializer)->build();

        $this->assertAttributeSame($serializer, 'serializer', $restAdapter);
    }

    public function testSetEventDispatcher()
    {
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $restAdapter = $this->getRestAdapterBuilder()->setEventDispatcher($eventDispatcher)->build();

        $this->assertAttributeSame($eventDispatcher, 'eventDispatcher', $restAdapter);
    }

    public function testSetLogger()
    {
        $logger = Mockery::mock(Logger::class);
        $restAdapter = $this->getRestAdapterBuilder()->setLogger($logger)->build();

        $this->assertAttributeSame($logger, 'logger', $restAdapter);
    }

    /**
     * @return RestAdapterBuilder
     */
    private function getRestAdapterBuilder()
    {
        return RestAdapter::builder()->setBaseUrl('http://example.com');
    }
}

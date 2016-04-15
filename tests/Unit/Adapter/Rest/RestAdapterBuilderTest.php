<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Rest;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use Mockery;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapterBuilder;
use Tebru\Retrofit\HttpClient\Adapter\Guzzle\GuzzleV5ClientAdapter;
use Tebru\Retrofit\HttpClient\Adapter\Guzzle\GuzzleV6ClientAdapter;
use Tebru\Retrofit\Subscriber\LogSubscriber;
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

        if (version_compare(Client::VERSION, '6', '<')) {
            $restAdapter = $this->getRestAdapterBuilder()->setHttpClient($client)->build();

            $this->assertAttributeInstanceOf(GuzzleV5ClientAdapter::class, 'httpClient', $restAdapter);
        } else {
            $restAdapter = $this->getRestAdapterBuilder()->setHttpClient($client)->build();

            $this->assertAttributeInstanceOf(GuzzleV6ClientAdapter::class, 'httpClient', $restAdapter);
        }
    }

    public function testSetClientAdapter()
    {
        $client = new Client();

        if (version_compare(Client::VERSION, '6', '<')) {
            $clientAdapter = new GuzzleV5ClientAdapter($client);
            $restAdapter = $this->getRestAdapterBuilder()->setClientAdapter($clientAdapter)->build();

            $this->assertAttributeInstanceOf(GuzzleV5ClientAdapter::class, 'httpClient', $restAdapter);
        } else {
            $clientAdapter = new GuzzleV6ClientAdapter($client);
            $restAdapter = $this->getRestAdapterBuilder()->setClientAdapter($clientAdapter)->build();

            $this->assertAttributeInstanceOf(GuzzleV6ClientAdapter::class, 'httpClient', $restAdapter);
        }

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
        $eventDispatcher->shouldReceive('addSubscriber')->times(1);

        $restAdapter = $this->getRestAdapterBuilder()->setEventDispatcher($eventDispatcher)->build();

        $this->assertAttributeSame($eventDispatcher, 'eventDispatcher', $restAdapter);
    }

    public function testOverrideLogSubscriber()
    {
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $eventDispatcher->shouldReceive('addSubscriber')->times(1);

        $subscriber = new LogSubscriber(Mockery::mock(LoggerInterface::class));
        $restAdapterBuilder = $this->getRestAdapterBuilder();
        $restAdapterBuilder->setEventDispatcher($eventDispatcher);
        $restAdapterBuilder->addSubscriber($subscriber);
        $restAdapterBuilder->ignoreLogSubscriber();
        $restAdapterBuilder->build();

        $this->assertAttributeSame([$subscriber], 'subscribers', $restAdapterBuilder);
    }

    public function testSetLogger()
    {
        $logger = Mockery::mock(LoggerInterface::class);
        $builder = $this->getRestAdapterBuilder();
        $builder->setLogger($logger)->build();

        $this->assertAttributeSame($logger, 'logger', $builder);
    }

    /**
     * @return RestAdapterBuilder
     */
    private function getRestAdapterBuilder()
    {
        return RestAdapter::builder()->setBaseUrl('http://example.com');
    }
}

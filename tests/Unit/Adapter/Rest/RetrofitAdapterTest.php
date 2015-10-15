<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Rest;

use JMS\Serializer\Serializer;
use Mockery;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Retrofit\Adapter\HttpClientAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Test\Mock\Service\MockServiceUrlRequest;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RetrofitAdapterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitAdapterTest extends MockeryTestCase
{
    /**
     * @var RestAdapter $adapter
     */
    private $adapter;

    protected function setUp()
    {
        parent::setUp();

        $this->adapter = RestAdapter::builder()
            ->setBaseUrl('')
            ->setHttpClient(Mockery::mock(HttpClientAdapter::class))
            ->build();
    }

    public function testWillUseObject()
    {
        $obj = new stdClass();
        $service = $this->adapter->create($obj);

        $this->assertSame($obj, $service);
    }

    public function testWillUseClass()
    {
        $obj = new stdClass();
        $service = $this->adapter->create(stdClass::class);

        $this->assertEquals($obj, $service);
    }

    public function testWillUseInterface()
    {
        $httpClient = Mockery::mock(HttpClientAdapter::class);
        $serializer = Mockery::mock(Serializer::class);
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $logger = Mockery::mock(LoggerInterface::class);
        $adapter = RestAdapter::builder()
            ->setBaseUrl('')
            ->setHttpClient($httpClient)
            ->setSerializer($serializer)
            ->setEventDispatcher($eventDispatcher)
            ->setLogger($logger)
            ->build();
        $generatedClass = new \Tebru\Retrofit\Generated\Tebru\Retrofit\Test\Mock\Service\MockServiceUrlRequest('', $httpClient, $serializer, $eventDispatcher, $logger);
        $service = $adapter->create(MockServiceUrlRequest::class);

        $this->assertEquals($generatedClass, $service);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     */
    public function testWillThrowExceptionOnInvalidParameter()
    {
        $this->adapter->create(1);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\RetrofitException
     */
    public function testWillThrowExceptionOnInvalidClass()
    {
        $this->adapter->create('Foo');
    }
}

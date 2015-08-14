<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Rest;

use Guzzle\Http\Client;
use JMS\Serializer\Serializer;
use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;
use Symfony\Component\Filesystem\Filesystem;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Mock\MockService;

/**
 * Class RetrofitAdapterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RestAdapter $adapter
     */
    private $adapter;

    protected function setUp()
    {
        $this->adapter = RestAdapter::builder()->setBaseUrl('')->build();
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $retrofit = new Retrofit(TEST_DIR . '/../cache/tests');
        $retrofit->cacheAll(TEST_DIR . '/Mock');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $filesystem = new Filesystem();
        $filesystem->remove(TEST_DIR . '/../cache/tests');
    }

    protected function tearDown()
    {
        Mockery::close();
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
        $httpClient = Mockery::mock(Client::class);
        $serializer = Mockery::mock(Serializer::class);
        $adapter = RestAdapter::builder()->setBaseUrl('')->setHttpClient($httpClient)->setSerializer($serializer)->build();
        $generatedClass = new \Tebru\Retrofit\Service\Tebru\Retrofit\Test\Mock\MockService('', $httpClient, $serializer);
        $service = $adapter->create(MockService::class);

        $this->assertEquals($generatedClass, $service);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\InvalidServiceTypeException
     */
    public function testWillThrowExceptionOnInvalidParameter()
    {
        $this->adapter->create(1);
    }

    /**
     * @expectedException \Tebru\Retrofit\Exception\InvalidServiceTypeException
     */
    public function testWillThrowExceptionOnInvalidClass()
    {
        $this->adapter->create('Foo');
    }
}

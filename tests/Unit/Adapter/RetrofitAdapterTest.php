<?php
/**
 * File RetrofitAdapterTest.php 
 */

namespace Tebru\Retrofit\Test\Unit\Adapter;

use GuzzleHttp\Client;
use JMS\Serializer\Serializer;
use Mockery;
use PHPUnit_Framework_TestCase;
use stdClass;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Service\Generated_8b6b4182e9c7c326764c6adad40eef2a\Generated_8b6b4182e9c7c326764c6adad40eef2a;
use Tebru\Retrofit\Test\Functional\Mock\MockService;

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
        $generatedClass = new Generated_8b6b4182e9c7c326764c6adad40eef2a('', $httpClient, $serializer);
        $service = $adapter->create(MockService::class);

        $this->assertEquals($generatedClass, $service);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWillThrowExceptionOnInvalidParameter()
    {
        $this->adapter->create(1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWillThrowExceptionOnInvalidClass()
    {
        $this->adapter->create('Foo');
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Rest;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;

/**
 * Class RestAdapterBuilderTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestAdapterBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $restAdapter = $this->getRestAdapterBuilder()->build();

        $this->assertTrue($restAdapter instanceof RestAdapter);
    }

    /**
     * @expectedException \LogicException
     */
    public function testNoBaseUrlThrowsException()
    {
        RestAdapter::builder()->build();
    }

    public function testWillUseCustomHttpClient()
    {
        $client = new Client();
        $restAdapter = $this->getRestAdapterBuilder()->setHttpClient($client);

        $this->assertAttributeEquals($client, 'httpClient', $restAdapter);
    }

    public function testWillUseCustomSerializer()
    {
        $serializer = SerializerBuilder::create()->build();
        $restAdapter = $this->getRestAdapterBuilder()->setSerializer($serializer);

        $this->assertAttributeEquals($serializer, 'serializer', $restAdapter);
    }

    private function getRestAdapterBuilder()
    {
        return RestAdapter::builder()->setBaseUrl('http://example.com');
    }
}

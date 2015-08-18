<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Rest;

use JMS\Serializer\SerializerBuilder;
use Mockery;
use Tebru\Retrofit\Adapter\HttpClientAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapterBuilder;
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

    public function testWillUseCustomSerializer()
    {
        $serializer = SerializerBuilder::create()->build();
        $restAdapter = $this->getRestAdapterBuilder()->setSerializer($serializer);

        $this->assertAttributeEquals($serializer, 'serializer', $restAdapter);
    }

    /**
     * @return RestAdapterBuilder
     */
    private function getRestAdapterBuilder()
    {
        return RestAdapter::builder()
            ->setBaseUrl('http://example.com')
            ->setHttpClient(Mockery::mock(HttpClientAdapter::class));
    }
}

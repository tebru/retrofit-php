<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use Tebru\Retrofit\Test\Mock\Service\MockServiceReturns;
use Tebru\Retrofit\Test\Mock\Traits\ClientMocks;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ReturnsClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnsClientGenerationTest extends MockeryTestCase
{
    use ClientMocks;

    public function testRawReturn()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceReturns $client */
        $client = $this->getClient(MockServiceReturns::class, $httpClient, $this->getSerializer());
        $response = $client->rawReturn();

        $this->assertSame('[]', $response);
    }

    public function testArrayReturn()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceReturns $client */
        $client = $this->getClient(MockServiceReturns::class, $httpClient, $this->getSerializer());
        $response = $client->arrayReturn();

        $this->assertSame([], $response);
    }

    public function testDeserializedReturn()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse($this->getSerializedUser()), 'GET', '/get', $headers);
        /** @var MockServiceReturns $client */
        $client = $this->getClient(MockServiceReturns::class, $httpClient, $this->getSerializer());
        $response = $client->deserializedReturn();

        $this->assertEquals($this->getUser(), $response);
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use Tebru\Retrofit\Http\Callback;
use Tebru\Retrofit\Test\Mock\Service\MockServiceAsync;
use Tebru\Retrofit\Test\Mock\Traits\ClientMocks;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class AsyncClientGenerationTest
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class AsyncClientGenerationTest extends MockeryTestCase
{
    use ClientMocks;

    public function testAsyncOptional()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceAsync $client */
        $client = $this->getClient(MockServiceAsync::class, $httpClient, $this->getSerializer());
        $response = $client->asyncOptional();

        $this->assertSame([], $response);
    }

    public function testAsyncNotOptionalWithCallback()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getAsyncHttpClient('GET', '/get', $headers);
        /** @var MockServiceAsync $client */
        $client = $this->getClient(MockServiceAsync::class, $httpClient, $this->getSerializer());
        $response = $client->asyncOptional($this->getCallback());

        $this->assertSame(null, $response);
    }

    public function testAsyncNotOptional()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getAsyncHttpClient('GET', '/get', $headers);
        /** @var MockServiceAsync $client */
        $client = $this->getClient(MockServiceAsync::class, $httpClient, $this->getSerializer());
        $response = $client->asyncNotOptional($this->getCallback());

        $this->assertSame(null, $response);
    }

    private function getCallback()
    {
        $callback = \Mockery::mock(Callback::class);

        return $callback;
    }
}

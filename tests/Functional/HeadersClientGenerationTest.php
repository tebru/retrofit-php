<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use Tebru\Retrofit\Test\Mock\Service\MockServiceHeaders;
use Tebru\Retrofit\Test\Mock\Traits\ClientMocks;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class HeadersClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeadersClientGenerationTest extends MockeryTestCase
{
    use ClientMocks;

    public function testNoMethodHeaders()
    {
        $headers = ['Host' => ['mockservice.com'], 'foo' => ['bar'], 'baz' => ['buzz'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->noMethodHeaders();

        $this->assertSame([], $response);
    }

    public function testOneMethodHeaders()
    {
        $headers = ['Host' => ['mockservice.com'], 'foo' => ['bar'], 'baz' => ['buzz'], 'kit' => ['kat'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->oneMethodHeader('kat');

        $this->assertSame([], $response);
    }

    public function testHeaderChangeName()
    {
        $headers = ['Host' => ['mockservice.com'], 'foo' => ['bar'], 'baz' => ['buzz'], 'kit' => ['kat'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var \Tebru\Retrofit\Test\Mock\Service\MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->headerChangeName('kat');

        $this->assertSame([], $response);
    }

    public function testOneHeaderOverwrite()
    {
        $headers = ['Host' => ['mockservice.com'], 'foo' => ['foo'], 'baz' => ['buzz'], 'kit' => ['kat'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var \Tebru\Retrofit\Test\Mock\Service\MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->headerOverwrite('foo', 'kat');

        $this->assertSame([], $response);
    }
}

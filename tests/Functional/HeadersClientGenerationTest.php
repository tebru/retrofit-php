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
        $headers = ['foo' => 'bar', 'baz' => 'buzz'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->noMethodHeaders();

        $this->assertSame([], $response);
    }

    public function testOneMethodHeaders()
    {
        $headers = ['foo' => 'bar', 'baz' => 'buzz', 'kit' => 'kat'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->oneMethodHeader('kat');

        $this->assertSame([], $response);
    }

    public function testHeaderChangeName()
    {
        $headers = ['foo' => 'bar', 'baz' => 'buzz', 'kit' => 'kat'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get', $headers);
        /** @var \Tebru\Retrofit\Test\Mock\Service\MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->headerChangeName('kat');

        $this->assertSame([], $response);
    }

    public function testOneHeaderOverwrite()
    {
        $headers = ['foo' => 'foo', 'baz' => 'buzz', 'kit' => 'kat'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get', $headers);
        /** @var \Tebru\Retrofit\Test\Mock\Service\MockServiceHeaders $client */
        $client = $this->getClient(MockServiceHeaders::class, $httpClient, $this->getSerializer());
        $response = $client->headerOverwrite('foo', 'kat');

        $this->assertSame([], $response);
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use Tebru\Retrofit\Test\Mock\Service\MockServiceBody;
use Tebru\Retrofit\Test\Mock\Traits\ClientMocks;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BodyClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyClientGenerationTest extends MockeryTestCase
{
    use ClientMocks;

    public function testSimpleBody()
    {
        $body = ['foo' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', [], $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->simpleBody($body);

        $this->assertSame([], $response);
    }

    public function testSimpleBodyChangeName()
    {
        $body = ['foo' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', [], $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->bodyChangeName($body);

        $this->assertSame([], $response);
    }

    public function testObjectBody()
    {
        $body = $this->getUser();
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', [], $this->getSerializedUser());
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBody($body);

        $this->assertSame([], $response);
    }

    public function testObjectBodyChangeName()
    {
        $body = $this->getUser();
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', [], $this->getSerializedUser());
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBodyChangeName($body);

        $this->assertSame([], $response);
    }

    public function testParts()
    {
        $body = ['foo' => 'foo', 'bar' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', [], $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->parts('foo', 'bar');

        $this->assertSame([], $response);
    }

    public function testPartsChangeName()
    {
        $body = ['foo' => 'foo', 'bar' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', [], $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->partsChangeName('foo', 'bar');

        $this->assertSame([], $response);
    }

    public function testJsonBody()
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = ['foo' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', $headers, $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->jsonBody($body);

        $this->assertSame([], $response);
    }

    public function testObjectJsonBody()
    {
        $headers = ['Content-Type' => 'application/json'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', $headers, $this->getSerializedUser());
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectJsonBody($this->getUser());

        $this->assertSame([], $response);
    }

    public function testPartsJsonBody()
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = ['foo' => 'foo', 'bar' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', $headers, $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->partsJsonBody('foo', 'bar');

        $this->assertSame([], $response);
    }

    public function testHeaderJsonBody()
    {
        $headers = ['foo' => 'bar', 'Content-Type' => 'application/json'];
        $body = ['foo' => 'bar'];
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post', $headers, $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->headerJsonBody('bar', $body);

        $this->assertSame([], $response);
    }
}

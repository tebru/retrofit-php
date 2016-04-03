<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use GuzzleHttp\Psr7\MultipartStream;
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
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, http_build_query($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->simpleBody($body);

        $this->assertSame([], $response);
    }

    public function testSimpleBodyChangeName()
    {
        $body = ['foo' => 'bar'];
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, http_build_query($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->bodyChangeName($body);

        $this->assertSame([], $response);
    }

    public function testObjectBody()
    {
        $body = $this->getUser();
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/json']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $this->getSerializedUser());
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBody($body);

        $this->assertSame([], $response);
    }

    public function testObjectBodyChangeName()
    {
        $body = $this->getUser();
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/json']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $this->getSerializedUser());
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBodyChangeName($body);

        $this->assertSame([], $response);
    }

    public function testObjectBodyFormEncoded()
    {
        $body = $this->getUser();
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $expected = http_build_query(json_decode($this->getSerializedUser(), true));
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $expected);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBodyAsFromEncoded($body);

        $this->assertSame([], $response);
    }

    public function testObjectBodyOptional()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, null);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBodyOptional();

        $this->assertSame([], $response);
    }

    public function testObjectBodyJsonSerializable()
    {
        $body = $this->getUser();
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $expected = http_build_query(json_decode(json_encode($body), true));
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $expected);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectBodyJsonSerializable($body);

        $this->assertSame([], $response);
    }

    public function testParts()
    {
        $body = ['foo' => 'foo', 'bar' => 'bar'];
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, http_build_query($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->parts('foo', 'bar');

        $this->assertSame([], $response);
    }

    public function testPartsChangeName()
    {
        $body = ['foo' => 'foo', 'bar' => 'bar'];
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/x-www-form-urlencoded']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, http_build_query($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->partsChangeName('foo', 'bar');

        $this->assertSame([], $response);
    }

    public function testJsonBody()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/json']];
        $body = ['foo' => 'bar'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, json_encode($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->jsonBody($body);

        $this->assertSame([], $response);
    }

    public function testObjectJsonBody()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/json']];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $this->getSerializedUser());
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->objectJsonBody($this->getUser());

        $this->assertSame([], $response);
    }

    public function testPartsJsonBody()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['application/json']];
        $body = ['foo' => 'foo', 'bar' => 'bar'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, json_encode($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->partsJsonBody('foo', 'bar');

        $this->assertSame([], $response);
    }

    public function testHeaderJsonBody()
    {
        $headers = ['Host' => ['mockservice.com'], 'foo' => ['bar'], 'Content-Type' => ['application/json']];
        $body = ['foo' => 'bar'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, json_encode($body));
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->headerJsonBody('bar', $body);

        $this->assertSame([], $response);
    }

    public function testHeaderMultipartBody()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['multipart/form-data']];
        $file = fopen(__FILE__, 'r');
        $body = new MultipartStream([['name' => 'foo', 'contents' => $file]]);
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->multipart(['foo' => __FILE__]);

        $this->assertSame([], $response);
    }

    public function testHeaderMultipartBodyWithParts()
    {
        $headers = ['Host' => ['mockservice.com'], 'Content-Type' => ['multipart/form-data']];
        $file = fopen(__FILE__, 'r');
        $body = new MultipartStream([['name' => 'foo', 'contents' => $file]]);
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers, $body);
        /** @var MockServiceBody $client */
        $client = $this->getClient(MockServiceBody::class, $httpClient, $this->getSerializer());
        $response = $client->multipartWithParts(__DIR__ . '/BodyClientGenerationTest.php');

        $this->assertSame([], $response);
    }
}

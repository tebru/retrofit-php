<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use Tebru\Retrofit\Test\Mock\Service\MockServiceUrlRequest;
use Tebru\Retrofit\Test\Mock\Traits\ClientMocks;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class UrlRequestClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class UrlRequestClientGenerationTest extends MockeryTestCase
{
    use ClientMocks;

    public function testSimpleGet()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleGet();

        $this->assertSame([], $response);
    }

    public function testSimplePost()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'POST', '/post', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simplePost();

        $this->assertSame([], $response);
    }

    public function testSimplePut()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'PUT', '/put', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simplePut();

        $this->assertSame([], $response);
    }

    public function testSimpleDelete()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'DELETE', '/delete', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleDelete();

        $this->assertSame([], $response);
    }

    public function testSimpleHead()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'HEAD', '/head', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleHead();

        $this->assertSame([], $response);
    }

    public function testSimpleOptions()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'OPTIONS', '/options', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleOptions();

        $this->assertSame([], $response);
    }

    public function testSimplePatch()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'PATCH', '/patch', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simplePatch();

        $this->assertSame([], $response);
    }

    public function testUrlParam()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', '/get/1', $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->urlParam(1);

        $this->assertSame([], $response);
    }

    public function testUrlQuery()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = '/get?foo=bar';
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', $url, $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->urlQuery();

        $this->assertSame([], $response);
    }

    public function testVariableQuery()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = '/get?foo=bar&baz=baz';
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', $url, $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->variableQuery('baz');

        $this->assertSame([], $response);
    }

    public function testVariableQueryChangeName()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = '/get?foo=bar&baz=baz';
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', $url, $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->variableQueryChangeName('baz');

        $this->assertSame([], $response);
    }

    public function testQueryMap()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = '/get?foo=bar&baz=baz&kit[kat]=1';
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', $url, $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->queryMap('baz', ['kit' => ['kat' => 1]]);

        $this->assertSame([], $response);
    }

    public function testQueryChangeName()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = '/get?foo=bar&baz=baz&kit[kat]=1';
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', $url, $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->queryMapChangeName('baz', ['kit' => ['kat' => 1]]);

        $this->assertSame([], $response);
    }

    public function testDefaultParams()
    {
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];
        $url = '/get?foo=&bar=1&baz=&kit=1&kat=1';
        $httpClient = $this->getHttpClient($this->getResponse(), 'GET', $url, $headers);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->defaultParams();

        $this->assertSame([], $response);
    }
}

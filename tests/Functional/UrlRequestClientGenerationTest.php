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
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleGet();

        $this->assertSame([], $response);
    }

    public function testSimplePost()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'POST', '/post');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simplePost();

        $this->assertSame([], $response);
    }

    public function testSimplePut()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'PUT', '/put');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simplePut();

        $this->assertSame([], $response);
    }

    public function testSimpleDelete()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'DELETE', '/delete');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleDelete();

        $this->assertSame([], $response);
    }

    public function testSimpleHead()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'HEAD', '/head');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleHead();

        $this->assertSame([], $response);
    }

    public function testSimpleOptions()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'OPTIONS', '/options');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simpleOptions();

        $this->assertSame([], $response);
    }

    public function testSimplePatch()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'PATCH', '/patch');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->simplePatch();

        $this->assertSame([], $response);
    }

    public function testUrlParam()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get/1');
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->urlParam(1);

        $this->assertSame([], $response);
    }

    public function testUrlQuery()
    {
        $url = '/get?foo=bar';
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', $url);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->urlQuery();

        $this->assertSame([], $response);
    }

    public function testVariableQuery()
    {
        $url = '/get?foo=bar&baz=baz';
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', $url);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->variableQuery('baz');

        $this->assertSame([], $response);
    }

    public function testVariableQueryChangeName()
    {
        $url = '/get?foo=bar&baz=baz';
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', $url);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->variableQueryChangeName('baz');

        $this->assertSame([], $response);
    }

    public function testQueryMap()
    {
        $url = '/get?foo=bar&baz=baz&kit[kat]=1';
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', $url);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->queryMap('baz', ['kit' => ['kat' => 1]]);

        $this->assertSame([], $response);
    }

    public function testQueryChangeName()
    {
        $url = '/get?foo=bar&baz=baz&kit[kat]=1';
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', $url);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->queryMapChangeName('baz', ['kit' => ['kat' => 1]]);

        $this->assertSame([], $response);
    }

    public function testDefaultParams()
    {
        $url = '/get?foo=&bar=1&baz=&kit=1&kat=1';
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', $url);
        /** @var MockServiceUrlRequest $client */
        $client = $this->getClient(MockServiceUrlRequest::class, $httpClient, $this->getSerializer());
        $response = $client->defaultParams();

        $this->assertSame([], $response);
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional;

use Tebru\Retrofit\Test\Mock\Service\MockServiceBaseUrl;
use Tebru\Retrofit\Test\Mock\Traits\ClientMocks;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BaseUrlClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BaseUrlClientGenerationTest extends MockeryTestCase
{
    use ClientMocks;

    public function testBaseUrl()
    {
        $httpClient = $this->getHttpClient($this->getRequest(), $this->getResponse(), 'GET', '/get?foo=bar&test=test', ['foo' => 'foo'], null, 'http://changebase.org');
        /** @var MockServiceBaseUrl $client */
        $client = $this->getClient(MockServiceBaseUrl::class, $httpClient, $this->getSerializer());
        $response = $client->baseUrl('http://changebase.org', 'foo', 'test');

        $this->assertSame([], $response);
    }
}

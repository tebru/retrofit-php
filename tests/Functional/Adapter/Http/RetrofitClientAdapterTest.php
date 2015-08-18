<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Functional\Adapter\Http;

use Tebru\Retrofit\Adapter\Http\RetrofitClientAdapter;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RetrofitClientAdapterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitClientAdapterTest extends MockeryTestCase
{
    public function testCanSend()
    {
        $client = new RetrofitClientAdapter();
        $response = $client->send('GET', 'http://google.com', ['Accept' => 'application/json'], ['foo' => 'bar']);

        $this->assertContains('Google', $response->getBody());
    }
}

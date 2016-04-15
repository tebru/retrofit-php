<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Http;

use GuzzleHttp\Psr7\Response;
use Tebru\Dynamo\Test\Unit\MockeryTestCase;

/**
 * Class ResponseTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseTest extends MockeryTestCase
{
    public function testCanGetBody()
    {
        $response = new Response(200, [], 'my body');

        $this->assertSame('my body', (string)$response->getBody());
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Adapter\Http;

use Tebru\Dynamo\Test\Unit\MockeryTestCase;
use Tebru\Retrofit\Adapter\Http\Response;

/**
 * Class ResponseTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseTest extends MockeryTestCase
{
    public function testCanGetBody()
    {
        $response = new Response('my body');

        $this->assertSame('my body', $response->getBody());
    }
}

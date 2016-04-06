<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ResponseTypeTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseTypeTest extends MockeryTestCase
{
    public function testGetResponseType()
    {
        $responseType = new ResponseType(['value' => 'array']);
        $this->assertSame('array', $responseType->getType());
    }
}

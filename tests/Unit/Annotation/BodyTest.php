<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BodyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyTest extends MockeryTestCase
{
    public function testJsonSerializable()
    {
        $body = new Body(['value' => '$body', 'jsonSerializable' => true]);
        $this->assertTrue($body->isJsonSerializable());
    }
}

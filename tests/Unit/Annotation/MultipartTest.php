<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class MultipartTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MultipartTest extends MockeryTestCase
{
    public function testGetBoundary()
    {
        $multipart = new Multipart(['boundary' => '1234']);
        $this->assertSame('1234', $multipart->getBoundary());
    }
}

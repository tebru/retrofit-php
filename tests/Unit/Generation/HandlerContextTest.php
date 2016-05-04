<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation;

use Mockery;
use Tebru\Dynamo\Model\Body;
use Tebru\Retrofit\Generation\HandlerContext;
use Tebru\Retrofit\Generation\Printer\ArrayPrinter;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class HandlerContextTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HandlerContextTest extends MockeryTestCase
{
    public function testGetters()
    {
        $annotations = Mockery::mock(AnnotationProvider::class);
        $body = Mockery::mock(Body::class);
        $printer = Mockery::mock(ArrayPrinter::class);

        $context = new HandlerContext($annotations, $body, $printer);

        $this->assertSame($annotations, $context->annotations());
        $this->assertSame($body, $context->body());
        $this->assertSame($printer, $context->printer());
    }
}

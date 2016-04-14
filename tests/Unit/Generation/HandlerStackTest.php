<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation;

use Mockery;
use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;
use Tebru\Retrofit\Generation\HandlerStack;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class HandlerStackTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HandlerStackTest extends MockeryTestCase
{
    public function testExecution()
    {
        $context = Mockery::mock(HandlerContext::class);
        $handler = Mockery::mock(Handler::class);

        $handler->shouldReceive('__invoke')->times(1)->with($context)->andReturn();

        $stack = new HandlerStack($context);
        $stack->push($handler);
        $stack->execute();
    }
}

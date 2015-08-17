<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Builder\Factory;

use Tebru\Retrofit\Generation\Builder\Factory\MethodBodyBuilderFactory;
use Tebru\Retrofit\Generation\Builder\MethodBodyBuilder;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class MethodBodyBuilderFactoryTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MethodBodyBuilderFactoryTest extends MockeryTestCase
{
    public function testCanCreateFactory()
    {
        $factory = new MethodBodyBuilderFactory();

        $this->assertInstanceOf(MethodBodyBuilderFactory::class, $factory);
    }

    public function testCanCreateMethodBodyBuilder()
    {
        $factory = new MethodBodyBuilderFactory();
        $builder = $factory->make();

        $this->assertInstanceOf(MethodBodyBuilder::class, $builder);
    }
}

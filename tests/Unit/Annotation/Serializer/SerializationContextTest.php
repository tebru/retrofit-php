<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * SerializationContextTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class SerializationContextTest extends MockeryTestCase
{
    public function testSimple()
    {
        $annotation = new SerializationContext([
            'groups' => ['test'],
            'serializeNull' => true,
            'version' => 1,
            'enableMaxDepthChecks' => true,
            'foo' => 'bar',
        ]);

        $this->assertEquals(['test'], $annotation->getGroups());
        $this->assertEquals(true, $annotation->getSerializeNull());
        $this->assertEquals(true, $annotation->getEnableMaxDepthChecks());
        $this->assertEquals(1, $annotation->getVersion());
        $this->assertEquals(['foo' => 'bar'], $annotation->getAttributes());
    }
}

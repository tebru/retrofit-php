<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * DeserializationContextTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class DeserializationContextTest extends MockeryTestCase
{
    public function testSimple()
    {
        $annotation = new DeserializationContext([
            'depth'         => 4,
            'groups'        => ['test'],
            'serializeNull' => true,
            'version'       => 1,
            'foo'           => 'bar',
        ]);

        $this->assertEquals(4, $annotation->getDepth());
        $this->assertEquals(['test'], $annotation->getGroups());
        $this->assertEquals(true, $annotation->getSerializeNull());
        $this->assertEquals(1, $annotation->getVersion());
        $this->assertEquals(['foo' => 'bar'], $annotation->getAttributes());
    }
}

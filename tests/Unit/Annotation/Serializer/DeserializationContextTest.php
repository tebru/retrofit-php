<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;

/**
 * DeserializationContextTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class DeserializationContextTest extends PHPUnit_Framework_TestCase
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

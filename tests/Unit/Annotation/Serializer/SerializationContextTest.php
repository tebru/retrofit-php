<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;

/**
 * SerializationContextTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class SerializationContextTest extends PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $annotation = new SerializationContext([
            'groups'        => ['test'],
            'serializeNull' => true,
            'version'       => 1,
            'foo'           => 'bar',
        ]);

        $this->assertEquals(['test'], $annotation->getGroups());
        $this->assertEquals(true, $annotation->getSerializeNull());
        $this->assertEquals(1, $annotation->getVersion());
        $this->assertEquals(['foo' => 'bar'], $annotation->getAttributes());
    }
}

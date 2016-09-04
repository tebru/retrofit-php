<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use PHPUnit_Framework_Error_Deprecated;
use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * DeserializationContextTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class DeserializationContextTest extends MockeryTestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     */
    public function testDeprecated()
    {
        new DeserializationContext([]);
    }

    public function testSimple()
    {
        $this->disableDeprecationWarning();

        $annotation = new DeserializationContext([
            'depth'         => 4,
            'groups'        => ['test'],
            'serializeNull' => true,
            'version'       => 1,
            'foo'           => 'bar',
        ]);

        $this->assertEquals(4, $annotation->getContext()['depth']);
        $this->assertEquals(['test'], $annotation->getContext()['groups']);
        $this->assertEquals(true, $annotation->getContext()['serializeNull']);
        $this->assertEquals(1, $annotation->getContext()['version']);
        $this->assertEquals('bar', $annotation->getContext()['foo']);

        $this->enableDeprecationWarning();
    }
}

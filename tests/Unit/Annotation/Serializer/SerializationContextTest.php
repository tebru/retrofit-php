<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use PHPUnit_Framework_Error_Deprecated;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * SerializationContextTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class SerializationContextTest extends MockeryTestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     */
    public function testDeprecated()
    {
        new SerializationContext([]);
    }

    public function testSimple()
    {
        $this->disableDeprecationWarning();

        $annotation = new SerializationContext([
            'groups' => ['test'],
            'serializeNull' => true,
            'version' => 1,
            'enableMaxDepthChecks' => true,
            'foo' => 'bar',
        ]);

        $this->assertEquals(['test'], $annotation->getContext()['groups']);
        $this->assertEquals(true, $annotation->getContext()['serializeNull']);
        $this->assertEquals(1, $annotation->getContext()['version']);
        $this->assertEquals(true, $annotation->getContext()['enableMaxDepthChecks']);
        $this->assertEquals('bar', $annotation->getContext()['foo']);

        $this->enableDeprecationWarning();
    }
}

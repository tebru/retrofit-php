<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use Tebru\Retrofit\Annotation\Serializer\SerializerContext;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * SerializerContextTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SerializerContextTest extends MockeryTestCase
{
    public function testSimple()
    {
        $annotation = new SerializerContext([
            'value' => [
                'groups' => ['test'],
                'serializeNull' => true,
                'version' => 1,
                'enableMaxDepthChecks' => true,
                'foo' => 'bar',
            ],
        ]);

        $this->assertEquals(['test'], $annotation->getContext()['groups']);
        $this->assertEquals(true, $annotation->getContext()['serializeNull']);
        $this->assertEquals(1, $annotation->getContext()['version']);
        $this->assertEquals(true, $annotation->getContext()['enableMaxDepthChecks']);
        $this->assertEquals('bar', $annotation->getContext()['foo']);
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation\Serializer;

use Tebru\Retrofit\Annotation\Serializer\DeserializerContext;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * DeserializerContextTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DeserializerContextTest extends MockeryTestCase
{
    public function testSimple()
    {
        $annotation = new DeserializerContext([
            'value' => [
                'depth'         => 4,
                'groups'        => ['test'],
                'serializeNull' => true,
                'version'       => 1,
                'foo'           => 'bar',
            ],
        ]);

        $this->assertEquals(4, $annotation->getContext()['depth']);
        $this->assertEquals(['test'], $annotation->getContext()['groups']);
        $this->assertEquals(true, $annotation->getContext()['serializeNull']);
        $this->assertEquals(1, $annotation->getContext()['version']);
        $this->assertEquals('bar', $annotation->getContext()['foo']);
    }
}

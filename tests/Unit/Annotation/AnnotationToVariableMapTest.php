<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use Tebru\Retrofit\Annotation\VariableMapper;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class AnnotationToVariableMapTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AnnotationToVariableMapTest extends MockeryTestCase
{
    /**
     * @dataProvider provideAnnotationToVariableMapAnnotations
     * @expectedException \OutOfBoundsException
     */
    public function testConstructorWillThrowException($class)
    {
        new $class([]);
    }

    /**
     * @dataProvider provideAnnotationToVariableMapAnnotations
     */
    public function testSimple($class)
    {
        /** @var VariableMapper $annotation */
        $annotation = new $class(['value' => 'foo']);

        $this->assertEquals('foo', $annotation->getVariableName());
        $this->assertEquals('foo', $annotation->getRequestKey());
        $this->assertEquals('$foo', $annotation->getVariable());
    }

    /**
     * @dataProvider provideAnnotationToVariableMapAnnotations
     */
    public function testOverrideName($class)
    {
        /** @var VariableMapper $annotation */
        $annotation = new $class(['value' => 'foo', 'var' => 'bar']);

        $this->assertEquals('bar', $annotation->getVariableName());
        $this->assertEquals('foo', $annotation->getRequestKey());
        $this->assertEquals('$bar', $annotation->getVariable());
    }

    public function provideAnnotationToVariableMapAnnotations()
    {
        return [
            [QueryMap::class],
            [Part::class],
            [Header::class],
            [Body::class],
            [Query::class],
            [BaseUrl::class],
        ];
    }
}

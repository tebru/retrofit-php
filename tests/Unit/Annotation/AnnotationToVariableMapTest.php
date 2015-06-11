<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Annotation\AnnotationToVariableMap;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\Url;

/**
 * Class AnnotationToVariableMapTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AnnotationToVariableMapTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider provideAnnotationToVariableMapAnnotations
     * @expectedException \OutOfRangeException
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
        /** @var AnnotationToVariableMap $annotation */
        $annotation = new $class(['value' => 'foo']);

        $this->assertEquals('foo', $annotation->getName());
        $this->assertEquals('foo', $annotation->getKey());
        $this->assertEquals('$foo', $annotation->getValue());
    }

    /**
     * @dataProvider provideAnnotationToVariableMapAnnotations
     */
    public function testOverrideName($class)
    {
        /** @var AnnotationToVariableMap $annotation */
        $annotation = new $class(['value' => 'foo', 'var' => 'bar']);

        $this->assertEquals('bar', $annotation->getName());
        $this->assertEquals('foo', $annotation->getKey());
        $this->assertEquals('$bar', $annotation->getValue());
    }

    public function provideAnnotationToVariableMapAnnotations()
    {
        return [
            [QueryMap::class],
            [Part::class],
            [Header::class],
            [Body::class],
            [Query::class],
            [Url::class],
        ];
    }
}

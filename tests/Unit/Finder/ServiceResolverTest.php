<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Finder;

use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Test\ServiceResolver\MultipleInterfaces\MultipleBar;
use Tebru\Retrofit\Test\ServiceResolver\MultipleInterfaces\MultipleFoo;
use Tebru\Retrofit\Test\ServiceResolver\OneInterface\OneFoo;

/**
 * Class ServiceResolverTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ServiceResolverTest extends PHPUnit_Framework_TestCase
{
    public function testOneInterface()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(TEST_DIR . '/ServiceResolver/OneInterface');

        $classes = $this->getClassNames([OneFoo::class]);

        $this->assertEquals($classes, $services);
    }

    public function testMultipleInterfaces()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(TEST_DIR . '/ServiceResolver/MultipleInterfaces');

        $classes = $this->getClassNames([MultipleBar::class, MultipleFoo::class]);

        $this->assertEquals($classes, $services);
    }

    public function testNoPhpFiles()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(TEST_DIR . '/ServiceResolver/NoPhpFiles');

        $this->assertEquals([], $services);
    }

    public function testNoInterfaces()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(TEST_DIR . '/ServiceResolver/NoInterfaces');

        $this->assertEquals([], $services);
    }

    public function testNoAnnotations()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(TEST_DIR . '/ServiceResolver/NoAnnotations');

        $this->assertEquals([], $services);
    }

    private function getClassNames(array $classes)
    {
        $return = [];
        foreach($classes as $class) {
            $return[] = '\\' . $class;
        }

        return $return;
    }

}

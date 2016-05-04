<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Finder;

use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Test\Mock\ServiceResolver\MultipleInterfaces\MultipleBar;
use Tebru\Retrofit\Test\Mock\ServiceResolver\MultipleInterfaces\MultipleFoo;
use Tebru\Retrofit\Test\Mock\ServiceResolver\OneInterface\OneFoo;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class ServiceResolverTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ServiceResolverTest extends MockeryTestCase
{
    public function testOneInterface()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(__DIR__ . '/../../Mock/ServiceResolver/OneInterface');

        $classes = $this->getClassNames([OneFoo::class]);

        $this->assertEquals($classes, $services);
    }

    public function testMultipleInterfaces()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(__DIR__ . '/../../Mock/ServiceResolver/MultipleInterfaces');

        $classes = $this->getClassNames([MultipleBar::class, MultipleFoo::class]);

        $this->assertEquals($classes, $services);
    }

    public function testNoPhpFiles()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(__DIR__ . '/../../Mock/ServiceResolver/NoPhpFiles');

        $this->assertEquals([], $services);
    }

    public function testNoInterfaces()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(__DIR__ . '/../../Mock/ServiceResolver/NoInterfaces');

        $this->assertEquals([], $services);
    }

    public function testNoAnnotations()
    {
        $serviceResolver = new ServiceResolver();
        $services = $serviceResolver->findServices(__DIR__ . '/../../Mock/ServiceResolver/NoAnnotations');

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

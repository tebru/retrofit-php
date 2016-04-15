<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit;

use Tebru\Dynamo\Generator;
use Tebru\Retrofit\Finder\ServiceResolver;

/**
 * Class Retrofit
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Retrofit
{
    const NAMESPACE_PREFIX = 'Tebru\Retrofit\Generated';

    /**
     * Registered services
     *
     * @var array $services
     */
    private $services = [];

    /**
     * Finds all services in a given source directory
     *
     * @var ServiceResolver $serviceResolver
     */
    private $serviceResolver;

    /**
     * Converts an interface to a class
     *
     * @var Generator
     */
    private $generator;

    /**
     * Constructor
     *
     * @param ServiceResolver $serviceResolver Finds service classes
     * @param Generator $generator
     */
    public function __construct(ServiceResolver $serviceResolver, Generator $generator)
    {
        $this->serviceResolver = $serviceResolver;
        $this->generator = $generator;
    }

    /**
     * Create a new builder
     *
     * @return RetrofitBuilder
     */
    public static function builder()
    {
        return new RetrofitBuilder();
    }

    /**
     * Register an array of classes
     *
     * @param array $services
     */
    public function registerServices(array $services)
    {
        foreach ($services as $service) {
            $this->registerService($service);
        }
    }

    /**
     * Register a single class
     *
     * @param $service
     */
    public function registerService($service)
    {
        $this->services[] = $service;
    }

    /**
     * Use the service resolver to find all the services dynamically
     *
     * @param string $srcDir
     * @return int Number of services cached
     */
    public function cacheAll($srcDir)
    {
        $this->services = $this->serviceResolver->findServices($srcDir);

        return $this->createCache();
    }

    /**
     * Creates cache files based on registered services
     *
     * @return int Number of services cached
     */
    public function createCache()
    {
        foreach ($this->services as $service) {
            $this->generator->createAndWrite($service);
        }

        return count($this->services);
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use LogicException;
use Tebru\Retrofit\Finder\ServiceResolver;

/**
 * Class Retrofit
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Retrofit
{
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
     * An array of proxy factories
     *
     * @var ProxyFactory[]
     */
    private $proxyFactories;

    /**
     * Constructor
     *
     * @param ServiceResolver $serviceResolver Finds service classes
     * @param ProxyFactory[] $proxyFactories
     */
    public function __construct(ServiceResolver $serviceResolver, array $proxyFactories)
    {
        $this->serviceResolver = $serviceResolver;
        $this->proxyFactories = $proxyFactories;
    }

    /**
     * Create a new builder
     *
     * @return RetrofitBuilder
     */
    public static function builder(): RetrofitBuilder
    {
        return new RetrofitBuilder();
    }

    /**
     * Register an array of classes
     *
     * @param array $services
     * @return void
     */
    public function registerServices(array $services): void
    {
        foreach ($services as $service) {
            $this->registerService($service);
        }
    }

    /**
     * Register a single class
     *
     * @param string $service
     * @return void
     */
    public function registerService(string $service): void
    {
        $this->services[] = $service;
    }

    /**
     * Use the service resolver to find all the services dynamically
     *
     * @param string $srcDir
     * @return int Number of services cached
     * @throws \RuntimeException
     * @throws \BadMethodCallException
     */
    public function createAll(string $srcDir): int
    {
        $this->services = $this->serviceResolver->findServices($srcDir);

        return $this->createServices();
    }

    /**
     * Creates cache files based on registered services
     *
     * @return int Number of services cached
     * @throws \RuntimeException
     * @throws \BadMethodCallException
     */
    public function createServices(): int
    {
        foreach ($this->services as $service) {
            $this->create($service);
        }

        return \count($this->services);
    }

    /**
     * Create a new service proxy given an interface name
     *
     * The returned proxy object should be used as if it's an
     * instance of the service provided.
     *
     * @param string $service
     * @return Proxy
     */
    public function create(string $service): Proxy
    {
        foreach ($this->proxyFactories as $proxyFactory) {
            $object = $proxyFactory->create($service);
            if ($object !== null) {
                return $object;
            }
        }

        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
        throw new LogicException(sprintf('Retrofit: Could not find a proxy factory for %s', $service));
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Dynamo\Event\MethodEvent;
use Tebru\Dynamo\Event\StartEvent;
use Tebru\Dynamo\Generator;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Generation\Listener\DynamoMethodListener;
use Tebru\Retrofit\Generation\Listener\DynamoStartListener;

/**
 * Class RetrofitBuilder
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitBuilder
{
    /**
     * @var string
     */
    private $cacheDir;
    
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    /**
     * @var ServiceResolver
     */
    private $serviceResolver;
    
    /**
     * @var Generator
     */
    private $generator;

    /**
     * @param string $cacheDir
     * @return $this
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;

        return $this;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @param ServiceResolver $serviceResolver
     * @return $this
     */
    public function setServiceResolver(ServiceResolver $serviceResolver)
    {
        $this->serviceResolver = $serviceResolver;

        return $this;
    }

    /**
     * @param Generator $generator
     * @return $this
     */
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;

        return $this;
    }

    /**
     * Build a retrofit instance
     *
     * @return Retrofit
     */
    public function build()
    {
        if (null === $this->eventDispatcher) {
            $this->eventDispatcher = new EventDispatcher();
        }

        $this->eventDispatcher->addListener(StartEvent::NAME, new DynamoStartListener());
        $this->eventDispatcher->addListener(MethodEvent::NAME, new DynamoMethodListener());

        if (null === $this->cacheDir) {
            $this->cacheDir = sys_get_temp_dir() . '/retrofit';
        }

        if (null === $this->serviceResolver) {
            $this->serviceResolver = new ServiceResolver();
        }

        if (null === $this->generator) {
            $this->generator = Generator::builder()
                ->setNamespacePrefix(Retrofit::NAMESPACE_PREFIX)
                ->setCacheDir($this->cacheDir . '/retrofit')
                ->setEventDispatcher($this->eventDispatcher)
                ->build();
        }

        return new Retrofit($this->serviceResolver, $this->generator);
    }
}

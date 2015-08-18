<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit;

use Mockery;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Dynamo\Event\MethodEvent;
use Tebru\Dynamo\Event\StartEvent;
use Tebru\Dynamo\Generator;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Generation\Listener\DynamoMethodListener;
use Tebru\Retrofit\Generation\Listener\DynamoStartListener;
use Tebru\Retrofit\HttpClient\ClientProvider;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\RetrofitBuilder;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RetrofitBuilderTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitBuilderTest extends MockeryTestCase
{
    public function testCanCreateBuilder()
    {
        $builder = new RetrofitBuilder(new ClientProvider());

        $this->assertInstanceOf(RetrofitBuilder::class, $builder);
    }

    public function testCanUseDefaultParameters()
    {
        $builder = new RetrofitBuilder();
        $retrofit = $builder->build();

        $this->assertInstanceOf(Retrofit::class, $retrofit);
    }

    public function testCanUseAllSetters()
    {
        $eventDispatcher = Mockery::mock(EventDispatcherInterface::class);
        $generator = Mockery::mock(Generator::class);
        $serviceResolvoer = Mockery::mock(ServiceResolver::class);

        $eventDispatcher->shouldReceive('addListener')->times(1)->with(StartEvent::NAME, Mockery::type(DynamoStartListener::class));
        $eventDispatcher->shouldReceive('addListener')->times(1)->with(MethodEvent::NAME, Mockery::type(DynamoMethodListener::class));

        $builder = Retrofit::builder()
            ->setCacheDir('')
            ->setEventDispatcher($eventDispatcher)
            ->setGenerator($generator)
            ->setServiceResolver($serviceResolvoer);

        $retrofit = $builder->build();

        $this->assertInstanceOf(Retrofit::class, $retrofit);
    }
}

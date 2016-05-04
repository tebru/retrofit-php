<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit;

use Mockery;
use Tebru\Dynamo\Generator;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\RetrofitBuilder;
use Tebru\Retrofit\Test\Mock\Service\MockServiceBody;
use Tebru\Retrofit\Test\Mock\Service\MockServiceUrlRequest;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class RetrofitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTest extends MockeryTestCase
{
    public function testCanCreate()
    {
        $retrofit = new Retrofit(Mockery::mock(ServiceResolver::class), Mockery::mock(Generator::class));

        $this->assertInstanceOf(Retrofit::class, $retrofit);
    }

    public function testCanCreateBuilder()
    {
        $builder = Retrofit::builder();

        $this->assertInstanceOf(RetrofitBuilder::class, $builder);
    }

    public function testRegisterService()
    {
        $retrofit = new Retrofit(Mockery::mock(ServiceResolver::class), Mockery::mock(Generator::class));
        $retrofit->registerService(MockServiceUrlRequest::class);

        $this->assertAttributeEquals([MockServiceUrlRequest::class], 'services', $retrofit);
    }

    public function testRegisterServices()
    {
        $retrofit = new Retrofit(Mockery::mock(ServiceResolver::class), Mockery::mock(Generator::class));
        $retrofit->registerServices([MockServiceUrlRequest::class, MockServiceBody::class]);

        $this->assertAttributeEquals([MockServiceUrlRequest::class, MockServiceBody::class], 'services', $retrofit);
    }

    public function testCacheAll()
    {
        $serviceResolver = Mockery::mock(ServiceResolver::class);

        $serviceResolver->shouldReceive('findServices')->times(1)->with('sourceDir')->andReturn([]);

        $retrofit = new Retrofit($serviceResolver, Mockery::mock(Generator::class));
        $numberCached = $retrofit->cacheAll('sourceDir');

        $this->assertEquals(0, $numberCached);
    }

    public function testCreateCache()
    {
        $serviceResolver = Mockery::mock(ServiceResolver::class);
        $generator = Mockery::mock(Generator::class);

        $generator->shouldReceive('createAndWrite')->times(1)->with(MockServiceUrlRequest::class)->andReturnNull();
        $generator->shouldReceive('createAndWrite')->times(1)->with(MockServiceBody::class)->andReturnNull();

        $retrofit = new Retrofit($serviceResolver, $generator);
        $retrofit->registerServices([MockServiceUrlRequest::class, MockServiceBody::class]);
        $numberCached = $retrofit->createCache();

        $this->assertEquals(2, $numberCached);
    }
}

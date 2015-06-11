<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit;

use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Mock\MockService;
use Tebru\Retrofit\Test\Mock\MockSimpleService;

/**
 * Class RetrofitTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTest extends PHPUnit_Framework_TestCase
{
    static private $cacheDir;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$cacheDir = TEST_DIR . '/../cache/test_retrofit';
    }

    protected function tearDown()
    {
        parent::tearDown();

        $filesystem = new Filesystem();
        $filesystem->remove(self::$cacheDir);
    }
    public function testRegisterService()
    {
        $retrofit = new Retrofit(self::$cacheDir);
        $retrofit->registerService(MockService::class);

        $this->assertAttributeEquals([MockService::class], 'services', $retrofit);
    }

    public function testRegisterServices()
    {
        $retrofit = new Retrofit(self::$cacheDir);
        $retrofit->registerServices([MockService::class, MockSimpleService::class]);

        $this->assertAttributeEquals([MockService::class, MockSimpleService::class], 'services', $retrofit);
    }

    public function testCreateCache()
    {
        $retrofit = new Retrofit(self::$cacheDir);
        $retrofit->registerServices([MockService::class, MockSimpleService::class]);
        $numberCached = $retrofit->createCache();

        $this->assertEquals(2, $numberCached);
    }

    public function testCacheAll()
    {
        $retrofit = new Retrofit(self::$cacheDir);
        $numberCached = $retrofit->cacheAll(TEST_DIR . '/Mock');

        $this->assertEquals(4, $numberCached);
    }
}

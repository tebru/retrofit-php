<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Cache;

use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tebru\Retrofit\Cache\CacheWriter;
use Tebru\Retrofit\Provider\GeneratedClassMetaDataProvider;

/**
 * Class CacheWriterTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class CacheWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem
     */
    static private $filesystem;

    static private $cacheDir;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$cacheDir = TEST_DIR . '/../cache/test_cache_writer';
        self::$filesystem = new Filesystem();
    }

    protected function tearDown()
    {
        Mockery::close();

        self::$filesystem->remove(self::$cacheDir);
    }

    public function testInstantiationWillUseDefaultDirectory()
    {
        $cacheWriter = new CacheWriter();

        $this->assertAttributeEquals(sys_get_temp_dir() . '/retrofit', 'cacheDir', $cacheWriter);
    }

    public function testInstantiationWillCreateDirectory()
    {
        $this->assertFalse(is_dir(self::$cacheDir));

        new CacheWriter(self::$cacheDir);

        $this->assertTrue(is_dir(self::$cacheDir));
    }

    public function testWrite()
    {
        $path = '/test';
        $filename = 'MyClass.php';

        $generatedClassProvider = Mockery::mock(GeneratedClassMetaDataProvider::class);
        $generatedClassProvider->shouldReceive('getFilePath')->times(1)->withNoArgs()->andReturn($path);
        $generatedClassProvider->shouldReceive('getFilenameShort')->times(1)->withNoArgs()->andReturn($filename);

        $cacheWriter = new CacheWriter(self::$cacheDir);
        $cacheWriter->write($generatedClassProvider, 'test');

        $fileContents = file_get_contents(self::$cacheDir .'/retrofit' . $path . '/' . $filename);

        $this->assertEquals("<?php\ntest", $fileContents);
    }
}

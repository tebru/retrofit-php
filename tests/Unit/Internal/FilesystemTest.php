<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Internal;

use org\bovigo\vfs\vfsStream;
use Tebru\Retrofit\Internal\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        vfsStream::setup('cache');
        $this->filesystem = new Filesystem();
    }

    public function testCreateDirectory()
    {
        $directory = vfsStream::url('cache/retrofit/Test');

        self::assertTrue($this->filesystem->makeDirectory($directory));
    }

    public function testCreateFile()
    {
        $directory = vfsStream::url('cache/retrofit/Test');
        $this->filesystem->makeDirectory($directory);
        $file = vfsStream::url('cache/retrofit/Test/Service.php');

        self::assertTrue($this->filesystem->put($file, 'foo'));
    }
}

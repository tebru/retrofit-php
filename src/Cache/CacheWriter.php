<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Cache;

use Symfony\Component\Filesystem\Filesystem;
use Tebru\Retrofit\Provider\GeneratedClassMetaDataProvider;

/**
 * Class CacheWriter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class CacheWriter
{
    /**
     * Retrofit cache directory name
     */
    const RETROFIT_CACHE_DIR = 'retrofit';

    /**
     * Cache directory
     *
     * @var string $cacheDir
     */
    private $cacheDir;

    /**
     * Filesystem object to manipulate filesystem
     *
     * @var Filesystem $filesystem
     */
    private $filesystem;

    /**
     * Constructor
     *
     * @param string $cacheDir
     */
    public function __construct($cacheDir = null)
    {
        if (null === $cacheDir) {
            $cacheDir = sys_get_temp_dir();
        }

        $this->filesystem = new Filesystem();
        $this->cacheDir = $cacheDir . DIRECTORY_SEPARATOR . self::RETROFIT_CACHE_DIR;

        $this->filesystem->mkdir($this->cacheDir);
    }

    /**
     * Write to retrofit cache
     *
     * @param GeneratedClassMetaDataProvider $generatedClassMetaDataProvider
     * @param string $contents
     */
    public function write(GeneratedClassMetaDataProvider $generatedClassMetaDataProvider, $contents)
    {
        $contents = "<?php\n" . $contents;
        $path = $this->cacheDir . $generatedClassMetaDataProvider->getFilePath();
        $filename = $path . DIRECTORY_SEPARATOR . $generatedClassMetaDataProvider->getFilenameShort();

        $this->filesystem->mkdir($path);
        $this->filesystem->dumpFile($filename, $contents);
    }
}

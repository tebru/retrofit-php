<?php
/**
 * File CacheWriter.php 
 */

namespace Tebru\Retrofit\Cache;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CacheWriter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class CacheWriter
{
    /**#@+
     * Cache constants
     */
    const RETROFIT_CACHE_DIR = 'retrofit';
    const RETROFIT_CACHE_FILE = 'php_retrofit_cache.php';
    /**#@-*/

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
    public function __construct($cacheDir)
    {
        if (null === $cacheDir) {
            $cacheDir = sprintf('/tmp/%s', self::RETROFIT_CACHE_DIR);
        }

        $this->filesystem = new Filesystem();
        $this->cacheDir = $cacheDir;

        $this->configureCache();
    }

    /**
     * Configures caches
     */
    private function configureCache()
    {
        $this->filesystem->mkdir($this->getRetrofitCacheDir());
    }

    /**
     * Get cache directory for retrofit
     *
     * @return string
     */
    public function getRetrofitCacheDir()
    {
        return sprintf('%s/%s', $this->cacheDir, self::RETROFIT_CACHE_DIR);
    }

    /**
     * Get retrofit cache filename
     *
     * @return string
     */
    public function getRetrofitCacheFile()
    {
        return sprintf('%s/%s', $this->getRetrofitCacheDir(), self::RETROFIT_CACHE_FILE);
    }

    /**
     * Write to retrofit cache
     *
     * @param string $contents
     * @param int $append
     */
    public function write($contents, $append = FILE_APPEND)
    {
        file_put_contents($this->getRetrofitCacheFile(), $contents, $append);
    }

    /**
     * Reset content in cache file
     */
    public function clean()
    {
        $this->write("<?php\n", null);
    }
}

<?php
/**
 * File Retrofit.php 
 */

namespace Tebru\Retrofit;

use Symfony\Component\Filesystem\LockHandler;
use Tebru\Retrofit\Adapter\RestAdapter;
use Tebru\Retrofit\Cache\CacheWriter;
use Tebru\Retrofit\Cache\InterfaceToClientConverter;
use Tebru\Retrofit\Finder\ServiceResolver;

/**
 * Class Retrofit
 *
 * Façade that manages registered services
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Retrofit
{
    /**
     * Lock file filename
     */
    const RETROFIT_LOCK_FILE = 'php_retrofit_cache.lock';

    /**
     * Registered services
     *
     * @var array $services
     */
    private $services = [];

    /**
     * Writes class to file
     *
     * @var CacheWriter $cacheWriter
     */
    private $cacheWriter;

    /**
     * Converts an interface to a rest client
     *
     * @var InterfaceToClientConverter $interfaceToClientConverter
     */
    private $interfaceToClientConverter;

    /**
     * Finds all services in a given source directory
     *
     * @var ServiceResolver $serviceResolver
     */
    private $serviceResolver;

    /**
     * Constructor
     *
     * @param string $cacheDir Location of cache directory
     */
    public function __construct($cacheDir = null)
    {
        $this->cacheWriter = new CacheWriter($cacheDir);
        $this->interfaceToClientConverter = new InterfaceToClientConverter();
        $this->serviceResolver = new ServiceResolver();
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
     * Loads cached class file into memory
     */
    public function load()
    {
        require_once $this->cacheWriter->getRetrofitCacheFile();
    }

    /**
     * Use the service resolver to find all the services dynamically
     *
     * If debug is false, it will only create the cache file if it doesn't already exist
     *
     * @param string $srcDir
     * @param bool $debug
     * @return int Number of services cached
     */
    public function cacheAll($srcDir, $debug = true)
    {
        $this->services = $this->serviceResolver->findServices($srcDir);

        return $this->createCache($debug);
    }

    /**
     * Creates cache file based on registered services
     *
     * If debug is false, it will only create the cache file if it doesn't already exist
     *
     * @param bool $debug
     * @return int Number of services cached
     */
    public function createCache($debug = true)
    {
        if (false === $debug && file_exists($this->cacheWriter->getRetrofitCacheFile())) {
            return null;
        }

        // blank file
        $this->cacheWriter->clean();

        // loop through registered services and write to file
        foreach ($this->services as $service) {
            $this->cacheClass($service);
        }

        return count($this->services);
    }

    /**
     * Writes a single class to file
     *
     * Attempts to get lock before writing, will block until lock is obtained
     *
     * @param string $interfaceName
     * @param bool $force
     * @return null
     */
    public function cacheClass($interfaceName, $force = false)
    {
        $name = md5($interfaceName);
        if (class_exists(sprintf(RestAdapter::SERVICE_NAME, $name, $name)) && !$force) {
            return null;
        }

        $lockHandler = new LockHandler(self::RETROFIT_LOCK_FILE);
        $lockHandler->lock(true);

        if (class_exists(sprintf(RestAdapter::SERVICE_NAME, $name, $name)) && !$force) {
            return null;
        }

        $this->cacheWriter->write($this->getClass($interfaceName));

        $lockHandler->release();
    }

    /**
     * Compiles a class from an interface name
     *
     * @param string $interfaceName
     * @return string
     */
    public function getClass($interfaceName)
    {
        return $this->interfaceToClientConverter->createRestClient($interfaceName);
    }
}

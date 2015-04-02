<?php
/**
 * File Retrofit.php 
 */

namespace Tebru\Retrofit;

use Symfony\Component\Filesystem\LockHandler;
use Tebru\Retrofit\Cache\CacheWriter;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Generator\RestClientGenerator;
use Tebru\Retrofit\Twig\PrintArrayFunction;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFunction;

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
     * Generates a rest client
     *
     * @var RestClientGenerator $restClientGenerator
     */
    private $restClientGenerator;

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
        $twig = $this->getTwig();
        $this->cacheWriter = new CacheWriter($cacheDir);
        $this->restClientGenerator = new RestClientGenerator($twig);
        $this->serviceResolver = new ServiceResolver();
    }

    /**
     * Set up twig environment
     *
     * @return Twig_Environment
     */
    private function getTwig()
    {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/Resources/Template');
        $twig = new Twig_Environment($loader);
        $twig->addFunction(new Twig_SimpleFunction('print_array', new PrintArrayFunction()));

        return $twig;
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

        $lockHandler = new LockHandler(self::RETROFIT_LOCK_FILE);
        $lockHandler->lock(true);

        // blank file
        $this->cacheWriter->clean();

        // loop through registered services and write to file
        foreach ($this->services as $service) {
            $this->cacheWriter->write($this->getClass($service));
        }

        $lockHandler->release();

        return count($this->services);
    }

    /**
     * Compiles a class from an interface name
     *
     * @param string $interfaceName
     * @return string
     */
    private function getClass($interfaceName)
    {
        return $this->restClientGenerator->generate($interfaceName);
    }
}

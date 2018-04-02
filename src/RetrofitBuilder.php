<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Doctrine\Common\Annotations\AnnotationReader;
use LogicException;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\Cache\Simple\ChainCache;
use Symfony\Component\Cache\Simple\PhpFilesCache;
use Tebru\AnnotationReader\AnnotationReaderAdapter;
use Tebru\Retrofit\Annotation as Annot;
use Tebru\Retrofit\Finder\ServiceResolver;
use Tebru\Retrofit\Internal\AnnotationHandler as AnnotHandler;
use Tebru\Retrofit\Internal\AnnotationProcessor;
use Tebru\Retrofit\Internal\CallAdapter\CallAdapterProvider;
use Tebru\Retrofit\Internal\CallAdapter\DefaultCallAdapterFactory;
use Tebru\Retrofit\Internal\Converter\ConverterProvider;
use Tebru\Retrofit\Internal\Converter\DefaultConverterFactory;
use Tebru\Retrofit\Internal\DefaultProxyFactory;
use Tebru\Retrofit\Internal\Filesystem;
use Tebru\Retrofit\Internal\ServiceMethod\ServiceMethodFactory;

/**
 * Class RetrofitBuilder
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitBuilder
{
    /**
     * A cache interface to be used in place of defaults
     *
     * If this is set, [@see RetrofitBuilder::$shouldCache] will be ignored for internal
     * caches, however, [@see RetrofitBuilder::$shouldCache] and
     * [@see RetrofitBuilder::$cacheDir] will still be used to cache proxy clients.
     *
     * @var CacheInterface
     */
    private $cache;

    /**
     * Directory to store generated proxy clients
     *
     * @var string
     */
    private $cacheDir;

    /**
     * A Retrofit http client used to make requests
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * The service's base url
     *
     * @var string
     */
    private $baseUrl;

    /**
     * An array of factories used to create [@see CallAdapter]s
     *
     * @var CallAdapterFactory[]
     */
    private $callAdapterFactories = [];

    /**
     * An array of factories used to convert types
     *
     * @var ConverterFactory[]
     */
    private $converterFactories = [];

    /**
     * An array of factories used to create [@see Proxy] objects
     *
     * @var ProxyFactory[]
     */
    private $proxyFactories = [];

    /**
     * An array of handlers used to modify the request based on an annotation
     *
     * @var AnnotationHandler[]
     */
    private $annotationHandlers = [];

    /**
     * If we should cache the proxies
     *
     * @var bool
     */
    private $shouldCache = false;

    /**
     * Override default cache adapters
     *
     * @param CacheInterface $cache
     * @return RetrofitBuilder
     */
    public function setCache(CacheInterface $cache): RetrofitBuilder
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Set the cache directory
     *
     * @param string $cacheDir
     * @return RetrofitBuilder
     */
    public function setCacheDir(string $cacheDir): RetrofitBuilder
    {
        $this->cacheDir = $cacheDir;

        return $this;
    }

    /**
     * Set the Retrofit http client
     *
     * @param HttpClient $client
     * @return RetrofitBuilder
     */
    public function setHttpClient(HttpClient $client): RetrofitBuilder
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Set the base url
     *
     * @param string $baseUrl
     * @return RetrofitBuilder
     */
    public function setBaseUrl(string $baseUrl): RetrofitBuilder
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Add a [@see CallAdapterFactory]
     *
     * @param CallAdapterFactory $callAdapterFactory
     * @return RetrofitBuilder
     */
    public function addCallAdapterFactory(CallAdapterFactory $callAdapterFactory): RetrofitBuilder
    {
        $this->callAdapterFactories[] = $callAdapterFactory;

        return $this;
    }

    /**
     * Add a [@see ConverterFactory]
     *
     * @param ConverterFactory $converterFactory
     * @return RetrofitBuilder
     */
    public function addConverterFactory(ConverterFactory $converterFactory): RetrofitBuilder
    {
        $this->converterFactories[] = $converterFactory;

        return $this;
    }

    /**
     * Add a [@see ProxyFactory]
     *
     * @param ProxyFactory $proxyFactory
     * @return RetrofitBuilder
     */
    public function addProxyFactory(ProxyFactory $proxyFactory): RetrofitBuilder
    {
        $this->proxyFactories[] = $proxyFactory;

        return $this;
    }

    /**
     * Add an [@see AnnotationHandler]
     *
     * @param string $annotationName
     * @param AnnotationHandler $annotationHandler
     * @return RetrofitBuilder
     */
    public function addAnnotationHandler(string $annotationName, AnnotationHandler $annotationHandler): RetrofitBuilder
    {
        $this->annotationHandlers[$annotationName] = $annotationHandler;

        return $this;
    }

    /**
     * Enable caching proxies
     *
     * @param bool $enable
     * @return RetrofitBuilder
     */
    public function enableCache(bool $enable = true): RetrofitBuilder
    {
        $this->shouldCache = $enable;

        return $this;
    }

    /**
     * Build a retrofit instance
     *
     * @return Retrofit
     * @throws \LogicException
     */
    public function build(): Retrofit
    {
        $defaultProxyFactory = $this->createDefaultProxyFactory();
        foreach ($this->proxyFactories as $proxyFactory) {
            if ($proxyFactory instanceof DefaultProxyFactoryAware) {
                $proxyFactory->setDefaultProxyFactory($defaultProxyFactory);
            }
        }
        $this->proxyFactories[] = $defaultProxyFactory;

        return new Retrofit(new ServiceResolver(), $this->proxyFactories);
    }

    /**
     * Creates the default proxy factory and all necessary dependencies
     *
     * @return ProxyFactory
     * @throws \LogicException
     */
    private function createDefaultProxyFactory(): ProxyFactory
    {
        if ($this->baseUrl === null) {
            throw new LogicException('Retrofit: Base URL must be provided');
        }

        if ($this->httpClient === null) {
            throw new LogicException('Retrofit: Must set http client to make requests');
        }

        if ($this->shouldCache && $this->cacheDir === null) {
            throw new LogicException('Retrofit: If caching is enabled, must specify cache directory');
        }

        $this->cacheDir .= '/retrofit';

        // add defaults to any user registered
        $this->callAdapterFactories[] = new DefaultCallAdapterFactory();
        $this->converterFactories[] = new DefaultConverterFactory();

        if ($this->cache === null) {
            $this->cache = $this->shouldCache === true
                ? new ChainCache([new ArrayCache(0, false), new PhpFilesCache('', 0, $this->cacheDir)])
                : new ArrayCache(0, false);
        }

        $httpRequestHandler = new AnnotHandler\HttpRequestAnnotHandler();

        /** @noinspection ClassConstantUsageCorrectnessInspection */
        $annotationHandlers = array_merge(
            [
                Annot\Body::class => new AnnotHandler\BodyAnnotHandler(),
                Annot\DELETE::class => $httpRequestHandler,
                Annot\Field::class => new AnnotHandler\FieldAnnotHandler(),
                Annot\FieldMap::class => new AnnotHandler\FieldMapAnnotHandler(),
                Annot\GET::class => $httpRequestHandler,
                Annot\HEAD::class => $httpRequestHandler,
                Annot\Header::class => new AnnotHandler\HeaderAnnotHandler(),
                Annot\HeaderMap::class => new AnnotHandler\HeaderMapAnnotHandler(),
                Annot\Headers::class => new AnnotHandler\HeadersAnnotHandler(),
                Annot\OPTIONS::class => $httpRequestHandler,
                Annot\Part::class => new AnnotHandler\PartAnnotHandler(),
                Annot\PartMap::class => new AnnotHandler\PartMapAnnotHandler(),
                Annot\PATCH::class => $httpRequestHandler,
                Annot\Path::class => new AnnotHandler\PathAnnotHandler(),
                Annot\POST::class => $httpRequestHandler,
                Annot\PUT::class => $httpRequestHandler,
                Annot\Query::class => new AnnotHandler\QueryAnnotHandler(),
                Annot\QueryMap::class => new AnnotHandler\QueryMapAnnotHandler(),
                Annot\QueryName::class => new AnnotHandler\QueryNameAnnotHandler(),
                Annot\REQUEST::class => $httpRequestHandler,
                Annot\Url::class => new AnnotHandler\UrlAnnotHandler(),
            ],
            $this->annotationHandlers
        );
        $serviceMethodFactory = new ServiceMethodFactory(
            new AnnotationProcessor($annotationHandlers),
            new CallAdapterProvider($this->callAdapterFactories),
            new ConverterProvider($this->converterFactories),
            new AnnotationReaderAdapter(new AnnotationReader(), $this->cache),
            $this->baseUrl
        );

        return new DefaultProxyFactory(
            new BuilderFactory(),
            new Standard(),
            $serviceMethodFactory,
            $this->httpClient,
            new Filesystem(),
            $this->shouldCache,
            $this->cacheDir
        );
    }
}

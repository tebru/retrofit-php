<?php

declare(strict_types=1);

namespace Tebru\Retrofit\Internal;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\Psr16Adapter;
use Symfony\Component\Cache\Exception\CacheException;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\Cache\Simple\ChainCache;
use Symfony\Component\Cache\Simple\NullCache;
use Symfony\Component\Cache\Simple\PhpFilesCache;

/**
 * @codeCoverageIgnore
 */
final class CacheProvider
{
    /**
     * Create a "file cache", chained to a "memory cache" depending on symfony/cache version
     *
     * @param string $cacheDir
     * @return CacheInterface
     * @throws CacheException
     */
    public static function createFileCache(string $cacheDir): CacheInterface
    {
        // >= Symfony 4.3
        if (class_exists('Symfony\Component\Cache\Psr16Cache')) {
            return new Psr16Cache(new ChainAdapter([
                new Psr16Adapter(self::createMemoryCache()),
                new PhpFilesAdapter('', 0, $cacheDir),
            ]));
        }

        return new ChainCache([
            self::createMemoryCache(),
            new PhpFilesCache('', 0, $cacheDir)
        ]);
    }

    /**
     * Create a "memory cache" depending on symfony/cache version
     * @return CacheInterface
     */
    public static function createMemoryCache(): CacheInterface
    {
        // >= Symfony 4.3
        if (class_exists('Symfony\Component\Cache\Psr16Cache')) {
            return new Psr16Cache(new ArrayAdapter(0, false));
        }

        return new ArrayCache(0, false);
    }

    /**
     * Create a "null" cache (for annotations) depending on symfony/cache version
     *
     * @return CacheInterface
     */
    public static function createNullCache(): CacheInterface
    {
        // >= Symfony 4.3
        if (class_exists('Symfony\Component\Cache\Psr16Cache')) {
            return new Psr16Cache(new NullAdapter());
        }

        return new NullCache();
    }
}

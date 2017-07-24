<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

/**
 * Any custom proxy factory that implements this interface will get an instance of the default
 * proxy factory through a setter. This is useful if you need to temporarily override how requests
 * are created for a subset of service methods, but for all other methods, you can delegate to
 * the default behavior.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface DefaultProxyFactoryAware
{
    /**
     * Set the default proxy factory
     *
     * @param ProxyFactory $proxyFactory
     * @return void
     */
    public function setDefaultProxyFactory(ProxyFactory $proxyFactory): void;
}

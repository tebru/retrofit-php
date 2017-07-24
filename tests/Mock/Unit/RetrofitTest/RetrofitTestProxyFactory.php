<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\Retrofit\DefaultProxyFactoryAware;
use Tebru\Retrofit\Proxy;
use Tebru\Retrofit\ProxyFactory;

/**
 * Class RetrofitTestProxyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTestProxyFactory implements ProxyFactory, DefaultProxyFactoryAware
{
    /**
     * @var ProxyFactory
     */
    private $proxyFactory;

    /**
     * Create a new [@see Proxy] from given service name
     *
     * Returns null if the factory cannot handle the service
     *
     * @param string $service
     * @return null|Proxy
     */
    public function create(string $service): ?Proxy
    {
        return new RetrofitTestDelegateProxy($this->proxyFactory->create(ApiClient::class));
    }

    /**
     * Set the default proxy factory
     *
     * @param ProxyFactory $proxyFactory
     * @return void
     */
    public function setDefaultProxyFactory(ProxyFactory $proxyFactory): void
    {
        $this->proxyFactory = $proxyFactory;
    }
}

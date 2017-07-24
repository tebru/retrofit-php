<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

/**
 * Interface ProxyFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ProxyFactory
{
    /**
     * Create a new [@see Proxy] from given service name
     *
     * Returns null if the factory cannot handle the service
     *
     * @param string $service
     * @return null|Proxy
     */
    public function create(string $service): ?Proxy;
}

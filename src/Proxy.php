<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

/**
 * Interface Proxy
 *
 * This represents an implementation of a service interface and should be able to handle
 * method calls on that interface.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Proxy
{
    /**
     * Constructs a [@see Call] object based on an interface method and arguments, then passes it through a
     * [@see CallAdapter] before returning.
     *
     * @param string $interfaceName
     * @param string $methodName
     * @param array $args
     * @return mixed
     */
    public function __handleRetrofitRequest(string $interfaceName, string $methodName, array $args);
}

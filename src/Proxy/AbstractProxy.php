<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Proxy;

use Tebru\Retrofit\HttpClient;
use Tebru\Retrofit\Internal\HttpClientCall;
use Tebru\Retrofit\Internal\ServiceMethod\ServiceMethodFactory;
use Tebru\Retrofit\Proxy;

/**
 * Class Proxy
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class AbstractProxy implements Proxy
{
    /**
     * Creates a [@see DefaultServiceMethod]
     *
     * @var ServiceMethodFactory
     */
    private $serviceMethodFactory;

    /**
     * A retrofit http client
     *
     * @var HttpClient
     */
    private $client;

    /**
     * Constructor
     *
     * @param ServiceMethodFactory $serviceMethodFactory
     * @param HttpClient $client
     */
    public function __construct(ServiceMethodFactory $serviceMethodFactory, HttpClient $client)
    {
        $this->serviceMethodFactory = $serviceMethodFactory;
        $this->client = $client;
    }

    /**
     * Constructs a [@see Call] object based on an interface method and arguments, then passes it through a
     * [@see CallAdapter] before returning.
     *
     * @param string $interfaceName
     * @param string $methodName
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     * @throws \LogicException
     */
    public function __handleRetrofitRequest(string $interfaceName, string $methodName, array $args)
    {
        $serviceMethod = $this->serviceMethodFactory->create($interfaceName, $methodName);

        return $serviceMethod->adapt(new HttpClientCall($this->client, $serviceMethod, $args));
    }
}

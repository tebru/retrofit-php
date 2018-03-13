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
    public const RETROFIT_NO_DEFAULT_VALUE = '__retrofit_no_default_value__';

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

    /** @noinspection MagicMethodsValidityInspection */
    /**
     * Constructs a [@see Call] object based on an interface method and arguments, then passes it through a
     * [@see CallAdapter] before returning.
     *
     * @param string $interfaceName
     * @param string $methodName
     * @param array $args
     * @param array $defaultArgs
     * @return mixed
     */
    public function __handleRetrofitRequest(string $interfaceName, string $methodName, array $args, array $defaultArgs)
    {
        $args = $this->createArgs($args, $defaultArgs);
        $serviceMethod = $this->serviceMethodFactory->create($interfaceName, $methodName);

        return $serviceMethod->adapt(new HttpClientCall($this->client, $serviceMethod, $args));
    }

    /**
     * Append any default args to argument array
     *
     * @param array $args
     * @param array $defaultArgs
     * @return array
     */
    private function createArgs(array $args, array $defaultArgs): array
    {
        $numProvidedArgs = \count($args);
        $numArgs = \count($defaultArgs);

        if ($numArgs === $numProvidedArgs) {
            return $args;
        }

        // get arguments from end that were not provided
        $appendedArgs = \array_slice($defaultArgs, $numProvidedArgs);

        return \array_merge($args, $appendedArgs);
    }
}

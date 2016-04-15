<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;

use JMS\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Retrofit\Adapter\HttpClientAdapter;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\HttpClient\ClientProvider;
use Tebru\Retrofit\Retrofit;

/**
 * Class RestAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestAdapter
{
    /**
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * @var HttpClientAdapter $httpClient
     */
    private $httpClient;

    /**
     * @var Serializer $serializer
     */
    private $serializer;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param string $baseUrl
     * @param HttpClientAdapter $httpClient
     * @param Serializer $serializer
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        $baseUrl,
        HttpClientAdapter $httpClient,
        Serializer $serializer,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Create a rest adapter builder
     *
     * @return RestAdapterBuilder
     */
    public static function builder()
    {
        return new RestAdapterBuilder(new ClientProvider());
    }

    /**
     * Create a new service
     *
     * @param string|mixed $service
     * @return mixed $service
     * @throws RetrofitException
     */
    public function create($service)
    {
        // if it's an object, we just want to return it
        if (is_object($service)) {
            return $service;
        }

        // if it's not a string, we don't know how to handle this type
        if (!is_string($service)) {
            throw new RetrofitException(sprintf('Could not create client. Expected object or string, got "%s"', gettype($service)));
        }

        // get the class as a string
        // if $service is already a class, use that, otherwise,
        if (class_exists($service)) {
            $class = $service;
        } elseif (interface_exists($service)) {
            $class = Retrofit::NAMESPACE_PREFIX . '\\' . $service;
        } else {
            throw new RetrofitException(sprintf('Could not create client. "%s" should be a class or interface.', $service));
        }

        return new $class($this->baseUrl, $this->httpClient, $this->serializer, $this->eventDispatcher);
    }
}

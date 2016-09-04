<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tebru\Retrofit\Adapter\DeserializerAdapter;
use Tebru\Retrofit\Adapter\HttpClientAdapter;
use Tebru\Retrofit\Adapter\SerializerAdapter;
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
     * API base url
     *
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * Http client
     *
     * @var HttpClientAdapter $httpClient
     */
    private $httpClient;

    /**
     * Symfony event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Serializer adapter
     *
     * @var SerializerAdapter $serializerAdapter
     */
    private $serializerAdapter;

    /**
     * Deserializer adapter
     *
     * @var DeserializerAdapter $deserializerAdapter
     */
    private $deserializerAdapter;

    /**
     * Constructor
     *
     * @param string $baseUrl
     * @param HttpClientAdapter $httpClient
     * @param EventDispatcherInterface $eventDispatcher
     * @param SerializerAdapter $serializerAdapter
     * @param DeserializerAdapter $deserializerAdapter
     */
    public function __construct(
        $baseUrl,
        HttpClientAdapter $httpClient,
        EventDispatcherInterface $eventDispatcher,
        SerializerAdapter $serializerAdapter = null,
        DeserializerAdapter $deserializerAdapter = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializerAdapter = $serializerAdapter;
        $this->deserializerAdapter = $deserializerAdapter;
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

        return new $class($this->baseUrl, $this->httpClient, $this->eventDispatcher, $this->serializerAdapter, $this->deserializerAdapter);
    }
}

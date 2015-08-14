<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;

use Guzzle\Http\ClientInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Tebru;
use Tebru\Retrofit\Exception\InvalidServiceTypeException;
use Tebru\Retrofit\Provider\GeneratedClassMetaDataProvider;

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
     * @var ClientInterface $httpClient
     */
    private $httpClient;

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * @var SerializationContext
     */
    private $serializationContext;

    /**
     * @var DeserializationContext
     */
    private $deserializationContext;

    /**
     * Constructor
     *
     * @param string $baseUrl
     * @param ClientInterface $httpClient
     * @param SerializerInterface $serializer
     * @param SerializationContext $serializationContext
     * @param DeserializationContext $deserializationContext
     */
    public function __construct(
        $baseUrl,
        ClientInterface $httpClient,
        SerializerInterface $serializer,
        SerializationContext $serializationContext = null,
        DeserializationContext $deserializationContext = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->serializationContext = $serializationContext;
        $this->deserializationContext = $deserializationContext;
    }

    /**
     * Create a rest adapter builder
     *
     * @return RestAdapterBuilder
     */
    public static function builder()
    {
        return new RestAdapterBuilder();
    }

    /**
     * Create a new service
     *
     * @param string|object $service
     * @return object $service
     * @throws InvalidServiceTypeException
     */
    public function create($service)
    {
        // if it's an object, we just want to return it
        if (is_object($service)) {
            return $service;
        }

        // if it's not a string, we don't know how to handle this type
        Tebru\assert(is_string($service), new InvalidServiceTypeException(sprintf('Could not create client. Expected object or string, got "%s"', gettype($service))));

        // get the class as a string
        // if $service is already a class, use that, otherwise,
        if (class_exists($service)) {
            $class = $service;
        } elseif (interface_exists($service)) {
            $class = GeneratedClassMetaDataProvider::NAMESPACE_PREFIX . '\\' . $service;
        } else {
            throw new InvalidServiceTypeException(sprintf('Could not create client. "%s" should be a class or interface.', $service));
        }

        return new $class(
            $this->baseUrl,
            $this->httpClient,
            $this->serializer,
            $this->serializationContext,
            $this->deserializationContext
        );
    }
}

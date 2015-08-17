<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;

use Guzzle\Http\ClientInterface;
use JMS\Serializer\SerializerInterface;
use Tebru;
use Tebru\Retrofit\Exception\RetrofitException;
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
     * @var ClientInterface $httpClient
     */
    private $httpClient;

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * Constructor
     *
     * @param string $baseUrl
     * @param ClientInterface $httpClient
     * @param SerializerInterface $serializer
     */
    public function __construct($baseUrl, ClientInterface $httpClient, SerializerInterface $serializer) {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
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

        return new $class($this->baseUrl, $this->httpClient, $this->serializer);
    }
}

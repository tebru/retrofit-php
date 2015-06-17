<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Tebru;
use Tebru\Retrofit\Exception\BaseUrlMissingException;

/**
 * Class RestAdapterBuilder
 *
 * Builds a rest adapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestAdapterBuilder
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
     * @var SerializationContext $serializationContext
     */
    private $serializationContext;

    /**
     * @var DeserializationContext $deserializationContext
     */
    private $deserializationContext;

    /**
     * Sets the base url for the rest client
     *
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    /**
     * Sets the http client used with rest client
     *
     * @param ClientInterface $httpClient
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Set the serializer used with rest client
     *
     * @param SerializerInterface $serializer
     * @return $this
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Set the jms serializer serialization context
     * @param SerializationContext $serializationContext
     * @return $this
     */
    public function setSerializationContext($serializationContext)
    {
        $this->serializationContext = $serializationContext;

        return $this;
    }

    /**
     * Set the jms serializer deserialization context
     *
     * @param DeserializationContext $deserializationContext
     * @return $this
     */
    public function setDeserializationContext($deserializationContext)
    {
        $this->deserializationContext = $deserializationContext;

        return $this;
    }

    /**
     * Build the rest adapter
     *
     * @return RestAdapter
     * @throws BaseUrlMissingException
     */
    public function build()
    {
        Tebru\assert(null !== $this->baseUrl, new BaseUrlMissingException(sprintf('Could not build RestAdapter with null $baseUrl')));

        if (null === $this->httpClient) {
            $this->httpClient = new Client();
        }

        if (null === $this->serializer) {
            $this->serializer = SerializerBuilder::create()->build();
        }

        $adapter = new RestAdapter(
            $this->baseUrl,
            $this->httpClient,
            $this->serializer,
            $this->serializationContext,
            $this->deserializationContext
        );

        return $adapter;
    }
}

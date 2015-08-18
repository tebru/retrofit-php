<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Tebru;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\HttpClient\ClientProvider;

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
     * Gets the http client that's available
     *
     * @var ClientProvider
     */
    private $clientProvider;

    /**
     * Constructor
     *
     * @param ClientProvider $clientProvider
     */
    public function __construct(ClientProvider $clientProvider)
    {
        $this->clientProvider = $clientProvider;
    }

    /**
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

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
     * Currently only supports guzzle clients
     *
     * @param mixed $httpClient
     * @return $this
     */
    public function setHttpClient($httpClient)
    {
        $this->clientProvider->setClient($httpClient);

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
     * Build the rest adapter
     *
     * @return RestAdapter
     * @throws RetrofitException
     */
    public function build()
    {
        if (null === $this->baseUrl) {
            throw new RetrofitException('Could not build RestAdapter with null $baseUrl');
        }

        if (null === $this->serializer) {
            $this->serializer = SerializerBuilder::create()->build();
        }

        $adapter = new RestAdapter($this->baseUrl, $this->clientProvider->getClient(), $this->serializer);

        return $adapter;
    }
}

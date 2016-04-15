<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Rest;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Tebru;
use Tebru\Retrofit\Adapter\HttpClientAdapter;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\HttpClient\ClientProvider;
use Tebru\Retrofit\Subscriber\LogSubscriber;

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
     * Client base url
     *
     * @var string $baseUrl
     */
    private $baseUrl;

    /**
     * JMS Serializer
     *
     * @var Serializer $serializer
     */
    private $serializer;

    /**
     * Symfony event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Array of event subscribers
     *
     * @var EventSubscriberInterface[]
     */
    private $subscribers = [];

    /**
     * Determine if we should use the default log subscriber
     */
    private $useLogSubscriber = true;

    /**
     * Psr logger
     *
     * @var LoggerInterface
     */
    private $logger;

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
     * @deprecated Will be removed in v3. Use setClientAdapter() instead.
     * @param mixed $httpClient
     * @return $this
     * @throws RetrofitException
     */
    public function setHttpClient($httpClient)
    {
        trigger_error(
           'Retrofit Deprecation: Setting an http client is deprecated and will be removed
            in v3.  Use RestAdapterBuilder::setClientAdapter() instead.',
            E_USER_DEPRECATED
        );

        $this->clientProvider->setClient($httpClient);

        return $this;
    }

    /**
     * Set the http client adapter to make requests
     *
     * @param HttpClientAdapter $clientAdapter
     * @return $this
     * @throws RetrofitException
     */
    public function setClientAdapter(HttpClientAdapter $clientAdapter)
    {
        $this->clientProvider->setClient($clientAdapter);

        return $this;
    }

    /**
     * Set the serializer used with rest client
     *
     * @param Serializer $serializer
     * @return $this
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Set the event dispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @return RestAdapterBuilder
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * Add a subscriber
     *
     * @param EventSubscriberInterface $subscriber
     * @return $this
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->subscribers[] = $subscriber;

        return $this;
    }

    /**
     * Do not use the default log subscriber;
     *
     * @return $this
     */
    public function ignoreLogSubscriber()
    {
        $this->useLogSubscriber = false;

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

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

        if (null === $this->eventDispatcher) {
            $this->eventDispatcher = new EventDispatcher();
        }

        foreach ($this->subscribers as $subscriber) {
            $this->eventDispatcher->addSubscriber($subscriber);
        }

        if (null === $this->logger) {
            $this->logger = new NullLogger();
        }

        if ($this->useLogSubscriber) {
            $this->eventDispatcher->addSubscriber(new LogSubscriber($this->logger));
        }

        $adapter = new RestAdapter(
            $this->baseUrl,
            $this->clientProvider->getClient(),
            $this->serializer,
            $this->eventDispatcher
        );

        return $adapter;
    }
}

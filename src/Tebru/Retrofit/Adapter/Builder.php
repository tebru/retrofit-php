<?php
/**
 * File Builder.php 
 */

namespace Tebru\Retrofit\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\SubscriberInterface;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use LogicException;

/**
 * Class Builder
 *
 * Builds a rest adapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Builder
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
     * An array of http client subscribers
     *
     * @var array $httpClientSubscribers
     */
    private $httpClientSubscribers = [];

    /**
     * An array of serializer subscribers
     *
     * @var array $serializerSubscribers
     */
    private $serializerSubscribers = [];

    /**
     * An array of serializer subscribing handlers
     *
     * @var array $serializerSubscribingHandlers
     */
    private $serializerSubscribingHandlers = [];

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
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Set the serializer used with rest client
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Add a subscriber to the http client
     *
     * @param SubscriberInterface $subscriber
     */
    public function addHttpClientSubscriber(SubscriberInterface $subscriber)
    {
        $this->httpClientSubscribers[] = $subscriber;
    }

    /**
     * Add a subscriber to the serializer
     *
     * @param EventSubscriberInterface $subscriber
     */
    public function addSerializerSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->serializerSubscribers[] = $subscriber;
    }

    /**
     * Add a subscribing handler to the serializer
     *
     * @param HandlerRegistryInterface $handler
     */
    public function addSerializerSubscribingHandler(HandlerRegistryInterface $handler)
    {
        $this->serializerSubscribingHandlers[] = $handler;
    }

    /**
     * Build the rest adapter
     *
     * @return RestAdapter
     */
    public function build()
    {
        $this->assertProperty($this->baseUrl);

        if (null === $this->httpClient) {
            $this->httpClient = new Client();
        }

        if (null === $this->serializer) {
            $serializerBuilder = SerializerBuilder::create();

            $serializerBuilder->configureListeners(function (EventDispatcherInterface $dispatcher) {
                foreach ($this->serializerSubscribers as $subscriber) {
                    $dispatcher->addSubscriber($subscriber);
                }
            });

            $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $registry) {
                foreach ($this->serializerSubscribingHandlers as $handler) {
                    $registry->registerSubscribingHandler($handler);
                }
            });

            $this->serializer = $serializerBuilder->build();
        }

        foreach ($this->httpClientSubscribers as $subscriber) {
            $this->httpClient->getEmitter()->attach($subscriber);
        }


        return new RestAdapter($this->baseUrl, $this->httpClient, $this->serializer);
    }

    /**
     * Make sure property is set
     *
     * @param null $property
     */
    private function assertProperty($property = null)
    {
        if (null === $property) {
            throw new LogicException('Property is not defined');
        }
    }
}

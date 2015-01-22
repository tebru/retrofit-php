<?php
/**
 * File RestAdapter.php 
 */

namespace Tebru\Retrofit\Adapter;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var EventDispatcherInterface $eventDispatcher
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param string $baseUrl
     * @param ClientInterface $httpClient
     * @param SerializerInterface $serializer
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct($baseUrl, ClientInterface $httpClient, SerializerInterface $serializer, EventDispatcherInterface $eventDispatcher)
    {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Create a rest adapter builder
     *
     * @return Builder
     */
    static public function builder()
    {
        return new Builder();
    }

    /**
     * Create a new service
     *
     * @param string $service
     *
     * @return $service
     */
    public function create($service)
    {
        $className = md5($service);
        $class = sprintf('\\Tebru\\Retrofit\\Service\\NSGenerated_%s\\Generated_%s', $className, $className);

        return new $class($this->baseUrl, $this->httpClient, $this->serializer, $this->eventDispatcher);
    }
}

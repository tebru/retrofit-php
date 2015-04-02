<?php
/**
 * File RestAdapter.php 
 */

namespace Tebru\Retrofit\Adapter\Rest;

use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use JMS\Serializer\SerializerInterface;

/**
 * Class RestAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestAdapter
{
    /**
     * Generated class name
     */
    const SERVICE_NAME = '\\Tebru\\Retrofit\\Service\\NSGenerated_%s\\Generated_%s';

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
    public function __construct($baseUrl, ClientInterface $httpClient, SerializerInterface $serializer)
    {
        $this->baseUrl = $baseUrl;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    /**
     * Create a rest adapter builder
     *
     * @return RestAdapterBuilder
     */
    static public function builder()
    {
        return new RestAdapterBuilder();
    }

    /**
     * Create a new service
     *
     * @param string|object $service
     * @return object $service
     */
    public function create($service)
    {
        // if it's an object, we just want to return it
        if (is_object($service)) {
            return $service;
        }

        // if it's not a string, we don't know how to handle this type
        if (!is_string($service)) {
            throw new InvalidArgumentException('Argument passed to create() must be an object or string');
        }

        // get the class as a string
        // if $service is already a class, use that, otherwise,
        if (class_exists($service)) {
            $class = $service;
        } elseif (interface_exists($service)) {
            $className = md5($service);
            $class = sprintf(self::SERVICE_NAME, $className, $className);
        } else {
            throw new InvalidArgumentException('String argument passed to create() must be a class or interface');
        }

        return new $class($this->baseUrl, $this->httpClient, $this->serializer);
    }
}

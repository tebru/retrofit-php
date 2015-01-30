<?php
/**
 * File RestAdapter.php 
 */

namespace Tebru\Retrofit\Adapter;

use GuzzleHttp\ClientInterface;
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
        $class = sprintf(self::SERVICE_NAME, $className, $className);

        return new $class($this->baseUrl, $this->httpClient, $this->serializer);
    }
}

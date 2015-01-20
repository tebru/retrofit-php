<?php
/**
 * File RestAdapter.php 
 */

namespace Tebru\Retrofit\Adapter;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\SerializerInterface;
use Tebru\Retrofit\RequestInterceptor;

/**
 * Class RestAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RestAdapter
{
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
     * @var RequestInterceptor $requestInterceptor
     */
    private $requestInterceptor;

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
     * @param RequestInterceptor $requestInterceptor
     */
    public function setRequestInterceptor(RequestInterceptor $requestInterceptor)
    {
        $this->requestInterceptor = $requestInterceptor;
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

        return new $class($this->baseUrl, $this->httpClient, $this->serializer, $this->requestInterceptor);
    }
}

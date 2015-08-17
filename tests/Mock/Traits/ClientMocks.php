<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Traits;

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Mockery;
use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Test\Mock\MockUser;

/**
 * Trait ClientMocks
 *
 * @author Nate Brunette <n@tebru.net>
 */
trait ClientMocks
{
    /**
     * @return RequestInterface
     */
    protected function getRequest()
    {
        return Mockery::mock(RequestInterface::class);
    }

    /**
     * @param string $responseBody
     * @return ResponseInterface
     */
    protected function getResponse($responseBody = '[]')
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->with(true)->andReturn($responseBody);

        return $response;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param $method
     * @param $uri
     * @param array $headers
     * @param null $body
     * @return ClientInterface
     */
    protected function getHttpClient(RequestInterface $request, ResponseInterface $response, $method, $uri, $headers = [], $body = null, $baseUrl = 'http://mockservice.com')
    {
        $httpClient = Mockery::mock(ClientInterface::class);

        $httpClient->shouldReceive('createRequest')->times(1)->with($method, $baseUrl . $uri, $headers, $body)->andReturn($request);
        $httpClient->shouldReceive('send')->times(1)->with($request)->andReturn($response);

        return $httpClient;
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer()
    {
        return SerializerBuilder::create()->build();
    }

    protected function getClient($service, ClientInterface $httpClient, SerializerInterface $serializer)
    {
        $builder = RestAdapter::builder()->setBaseUrl('http://mockservice.com');
        $builder->setHttpClient($httpClient);
        $builder->setSerializer($serializer);

        return $builder->build()->create($service);
    }

    protected function getUser()
    {
        $user = new MockUser();
        $user->id = 1;
        $user->name = 'Nate';

        return $user;
    }

    protected function getSerializedUser()
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($this->getUser(), 'json');
    }
}

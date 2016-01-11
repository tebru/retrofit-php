<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Traits;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use LogicException;
use Mockery;
use Tebru\Retrofit\Adapter\HttpClientAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Http\Callback;
use Tebru\Retrofit\Test\Mock\MockUser;

/**
 * Trait ClientMocks
 *
 * @author Nate Brunette <n@tebru.net>
 */
trait ClientMocks
{
    /**
     * @param string $responseBody
     * @return Response
     */
    protected function getResponse($responseBody = '[]')
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('getBody')->times(1)->with()->andReturn($responseBody);

        return $response;
    }

    /**
     * @param Response $response
     * @param $method
     * @param $uri
     * @param array $headers
     * @param null $body
     * @return HttpClientAdapter
     */
    protected function getHttpClient(Response $response, $method, $uri, $headers = [], $body = null, $baseUrl = 'http://mockservice.com')
    {
        $httpClient = Mockery::mock(HttpClientAdapter::class);
        $request = new Request($method, $baseUrl . $uri, $headers, $body);
        $request->getBody();

        $httpClient->shouldReceive('send')->times(1)->with(Mockery::on(function ($argument) use ($request) {
            /** @var Request $argument */
            if ($request->getMethod() !== $argument->getMethod()) {
                throw new LogicException('Request methods not equal');
            }

            if ((string)$request->getUri() !== (string)$argument->getUri()) {
                throw new LogicException('Request uris not equal');
            }

            if ($request->getHeaders() !== $argument->getHeaders()) {
                throw new LogicException('Request headers not equal');
            }

            if ((string)$request->getBody() !== (string)$argument->getBody()) {
                throw new LogicException('Request bodies not equal');
            }

            return true;
        }))->andReturn($response);

        return $httpClient;
    }

    /**
     * @return HttpClientAdapter
     */
    protected function getAsyncHttpClient()
    {
        $httpClient = Mockery::mock(HttpClientAdapter::class);
        $httpClient->shouldReceive('sendAsync')->times(1)->with(Mockery::type(Request::class), Mockery::type(Callback::class))->andReturnNull();

        return $httpClient;
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer()
    {
        return SerializerBuilder::create()->build();
    }

    protected function getClient($service, HttpClientAdapter $httpClient, SerializerInterface $serializer)
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

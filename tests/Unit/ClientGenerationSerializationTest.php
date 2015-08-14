<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Test\Unit;

use Guzzle\Http\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Mockery;
use PhpParser;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Adapter\Rest\RestAdapterBuilder;
use Tebru\Retrofit\Retrofit;
use Tebru\Retrofit\Test\Mock\MockService;
use Tebru\Retrofit\Test\Mock\MockUser;

/**
 * ClientGenerationSerializationTest
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class ClientGenerationSerializationTest extends PHPUnit_Framework_TestCase
{
    const BASE_URL = 'http://mockservice.com';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $retrofit = new Retrofit(TEST_DIR . '/../cache/tests');
        $retrofit->cacheAll(TEST_DIR . '/Mock');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $filesystem = new Filesystem();
        $filesystem->remove(TEST_DIR . '/../cache/tests');
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testClassSerializationContextIfNoAnnotation()
    {
        $serializationContext = SerializationContext::create();

        $user = $this->getUser();
        $jsonUser = $this->serializeUser($user);

        $builder = $this->createBuilder('POST', '/post', ['body' => $jsonUser], [], [], true);
        $builder->setSerializationContext($serializationContext);

        $adapter = $builder->build();
        /** @var MockService $service */
        $service = $adapter->create(MockService::class);

        $service->postWithJsonBodyObject($user);

        $this->setExpectedException('LogicException', 'This context was already initialized and is immutable');
        $serializationContext->setAttribute('foo', 'bar');
    }

    public function testCustomSerializationContextIfAnnotation()
    {
        $serializationContext = SerializationContext::create();

        $user = $this->getUser();
        $jsonUser = $this->serializeUser($user, ['Default', 'test']);

        $builder = $this->createBuilder('POST', '/post', ['body' => $jsonUser], [], [], true);
        $builder->setSerializationContext($serializationContext);

        $adapter = $builder->build();
        /** @var MockService $service */
        $service = $adapter->create(MockService::class);

        $service->postSerializationContext($this->getUser());

        $serializationContext->setAttribute('foo', 'bar');
    }

    public function testClassDeserializationContextIfNoAnnotation()
    {
        $deserializationContext = DeserializationContext::create();

        $builder = $this->createBuilder('GET', '/get');
        $builder->setDeserializationContext($deserializationContext);

        $adapter = $builder->build();
        /** @var MockService $service */
        $service = $adapter->create(MockService::class);

        $user = $service->getDeserializedReturn();
        $this->assertEquals($this->getUser(), $user);

        $this->setExpectedException('LogicException', 'This context was already initialized and is immutable');
        $deserializationContext->setAttribute('foo', 'bar');
    }

    public function testCustomDeserializationContextIfAnnotation()
    {
        $deserializationContext = DeserializationContext::create();

        $user = $this->getUser();
        $jsonUser = $this->serializeUser($user, ['Default', 'test']);

        $builder = $this->createBuilder('POST', '/post', ['body' => $jsonUser], [], [], true);
        $builder->setDeserializationContext($deserializationContext);

        $adapter = $builder->build();
        /** @var MockService $service */
        $service = $adapter->create(MockService::class);

        $user = $service->postSerializationContext($this->getUser());
        $this->assertEquals($this->getUser(), $user);

        $deserializationContext->setAttribute('foo', 'bar');
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $options
     * @param array  $query
     * @param array  $headers
     * @param bool   $jsonBody
     * @param bool   $baseUrl
     *
     * @return RestAdapterBuilder
     */
    private function createBuilder($method, $path, $options = [], $query = [], $headers = [], $jsonBody = false, $baseUrl = false)
    {
        $request = Mockery::mock(RequestInterface::class);

        if (!empty($query)) {
            $request->shouldReceive('setQuery')->times(1)->with($query)->andReturnNull();
        }

        if (!empty($headers)) {
            $request->shouldReceive('addHeaders')->times(1)->with($headers)->andReturnNull();
        }

        if (true === $jsonBody) {
            $request->shouldReceive('hasHeader')->times(1)->with('Content-Type')->andReturn(false);
            $request->shouldReceive('setHeader')->times(1)->with('Content-Type', 'application/json')->andReturnNull();
        }

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->times(1)->withNoArgs()->andReturn($this->serializeUser($this->getUser()));

        $httpClient = Mockery::mock(ClientInterface::class);

        $requestUrl = ($baseUrl) ? $path : self::BASE_URL . $path;
        $httpClient->shouldReceive('createRequest')
                   ->times(1)
                   ->with($method, $requestUrl, $options)
                   ->andReturn($request);
        $httpClient->shouldReceive('send')
                   ->times(1)
                   ->with($request)
                   ->andReturn($response);

        $builder = RestAdapter::builder()->setBaseUrl(self::BASE_URL);
        $builder->setHttpClient($httpClient);

        return $builder;
    }

    private function getUser()
    {
        $user = new MockUser();
        $user->id = 1;
        $user->name = 'Nate';
        $user->email = 'n@tebru.net';

        return $user;
    }

    private function serializeUser(MockUser $user, $groups = ['Default'])
    {
        $serializer = SerializerBuilder::create()->build();

        $context = SerializationContext::create()->setGroups($groups);

        return $serializer->serialize($user, 'json', $context);
    }
}

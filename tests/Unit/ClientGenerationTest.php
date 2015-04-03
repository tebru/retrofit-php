<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use JMS\Serializer\SerializerBuilder;
use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Test\Mock\MockService;
use Tebru\Retrofit\Test\Mock\MockServiceHeaders;
use Tebru\Retrofit\Test\Mock\MockSimpleService;
use Tebru\Retrofit\Test\Mock\MockUser;

/**
 * Class ClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ClientGenerationTest extends PHPUnit_Framework_TestCase
{
    const BASE_URL = 'http://mockservice.com';

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testSimpleGet()
    {
        $this->createClient(MockSimpleService::class, 'GET', '/get')->simpleGet();
    }

    public function testSimplePost()
    {
        $this->createClient(MockSimpleService::class, 'POST', '/post')->simplePost();
    }

    public function testSimplePut()
    {
        $this->createClient(MockSimpleService::class, 'PUT', '/put')->simplePut();
    }

    public function testSimpleDelete()
    {
        $this->createClient(MockSimpleService::class, 'DELETE', '/delete')->simpleDelete();
    }

    public function testSimpleHead()
    {
        $this->createClient(MockSimpleService::class, 'HEAD', '/head')->simpleHead();
    }

    public function testSimpleOptions()
    {
        $this->createClient(MockSimpleService::class, 'OPTIONS', '/options')->simpleOptions();
    }

    public function testSimplePatch()
    {
        $this->createClient(MockSimpleService::class, 'PATCH', '/patch')->simplePatch();
    }

    public function testGetWithVar()
    {
        $this->createClient(MockService::class, 'GET', '/get/1')->getWithVar(1);
    }

    public function testGetWithQuery()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], ['foo' => 'bar'])->getWithQuery();
    }

    public function testGetWithQueryDynamic()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz'])->getWithQueryDynamic('buzz');
    }

    public function testCanChangeQueryVar()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz'])->canChangeQueryVar('buzz');
    }

    public function testGetWithQueryMap()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz', ['bing' => 'bong']])->getWithQueryMap('buzz', ['bing' => 'bong']);
    }

    public function testGetWithQueryMapNested()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz', ['map' => ['bing' => 'bong'], 'bloop' => 'loop']])->getWithQueryMapNested('buzz', ['map' => ['bing' => 'bong'], 'bloop' => 'loop']);
    }

    public function testCanChangeQueryMapVar()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz', ['bing' => 'bong']])->canChangeQueryMapVar('buzz', ['bing' => 'bong']);
    }

    public function testPostWithSimpleBody()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['body' => ['foo' => 'bar']], [], [])->postWithSimpleBody(['foo' => 'bar']);
    }

    public function testCanChangeBodyVar()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['body' => ['foo' => 'bar']], [], [])->canChangeBodyVar(['foo' => 'bar']);
    }

    public function testPostWithObjectBody()
    {
        $user = $this->getUser();
        $jsonUser = $this->serializeUser($user);
        $this->createClient(MockService::class, 'POST', '/post', ['body' => $jsonUser], [], [])->postWithObjectBody($user);
    }

    public function testPostWithPart()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['body' => ['foo' => 'bar']], [], [])->postWithPart('bar');
    }

    public function testPostWithMultipleParts()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['body' => ['foo' => 'bar', 'baz' => 'buzz']], [], [])->postWithMultipleParts('bar', 'buzz');
    }

    public function testCanChangePartVar()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['body' => ['foo' => 'bar']], [], [])->canChangePartVar('bar');
    }

    public function testPostWithJsonBody()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['json' => ['foo' => 'bar']], [], [], true)->postWithJsonBody(['foo' => 'bar']);
    }

    public function testPartWithJsonBody()
    {
        $this->createClient(MockService::class, 'POST', '/post', ['json' => ['foo' => 'bar', 'baz' => 'buzz']], [], [], true)->postWithJsonBodyParts('bar', 'buzz');
    }

    public function testJsonBodyWithObject()
    {
        $user = $this->getUser();
        $jsonUser = $this->serializeUser($user);
        $this->createClient(MockService::class, 'POST', '/post', ['body' => $jsonUser], [], [], true)->postWithJsonBodyObject($user);
    }

    public function testGetWithHeader()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], [], ['foo' => 'bar'])->getWithHeader('bar');
    }

    public function testCanChangeHeaderVar()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], [], ['foo' => 'bar'])->canChangeHeaderVar('bar');
    }

    public function testGetWithMultipleHeaders()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], [], ['foo' => 'bar', 'baz' => 'buzz'])->getWithMultipleHeaders('bar', 'buzz');
    }

    public function testGetWithStaticHeaders()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], [], ['Foo' => 'bar'])->getWithStaticHeaders();
    }

    public function testGetWithStaticHeadersList()
    {
        $this->createClient(MockService::class, 'GET', '/get', [], [], ['Foo' => 'bar', 'Baz' => 'buzz'])->getWithStaticHeadersList();
    }

    public function testRawReturn()
    {
        $user = $this->createClient(MockService::class, 'GET', '/get')->getRawReturn();
        $this->assertSame($this->serializeUser($this->getUser()), $user);
    }

    public function testArrayReturn()
    {
        $user = $this->createClient(MockService::class, 'GET', '/get')->getArrayReturn();
        $this->assertSame(['id' => 1, 'name' => 'Nate'], $user);
    }

    public function testDefaultReturnIsArray()
    {
        $user = $this->createClient(MockService::class, 'GET', '/get')->getDefaultReturnIsArray();
        $this->assertSame(['id' => 1, 'name' => 'Nate'], $user);
    }

    public function testDeserializedReturn()
    {
        $user = $this->createClient(MockService::class, 'GET', '/get')->getDeserializedReturn();
        $this->assertEquals($this->getUser(), $user);
    }

    public function testNoHeader()
    {
        $this->createClient(MockServiceHeaders::class, 'GET', '/get', [], [], ['foo' => 'bar', 'baz' => 'buzz'])->noHeaders();
    }

    public function testOneHeader()
    {
        $this->createClient(MockServiceHeaders::class, 'GET', '/get', [], [], ['foo' => 'bar', 'baz' => 'buzz', 'kit' => 'kat'])->oneHeader('kat');
    }

    public function testHeaderOverwrite()
    {
        $this->createClient(MockServiceHeaders::class, 'GET', '/get', [], [], ['foo' => 'foo', 'baz' => 'buzz', 'kit' => 'kat'])->headerOverwrite('foo', 'kat');
    }

    /**
     * @param $service
     * @param $method
     * @param $path
     * @param array $options
     * @param array $query
     * @param array $headers
     * @param bool $jsonBody
     * @return MockService|MockServiceHeaders|MockSimpleService
     * @throws \InvalidArgumentException
     */
    private function createClient($service, $method, $path, $options = [], $query = [], $headers = [], $jsonBody = false)
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

        $httpClient->shouldReceive('createRequest')
            ->times(1)
            ->with($method, self::BASE_URL . $path, $options)
            ->andReturn($request);
        $httpClient->shouldReceive('send')
            ->times(1)
            ->with($request)
            ->andReturn($response);

        $builder = RestAdapter::builder()->setBaseUrl(self::BASE_URL);
        $builder->setHttpClient($httpClient);
        $restAdapter = $builder->build();

        return $restAdapter->create($service);
    }
    private function getUser()
    {
        $user = new MockUser();
        $user->id = 1;
        $user->name = 'Nate';

        return $user;
    }

    private function serializeUser(MockUser $user)
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->serialize($user, 'json');
    }
}

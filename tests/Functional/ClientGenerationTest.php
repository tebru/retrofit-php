<?php
/**
 * File ClientGenerationTest.php 
 */

namespace Tebru\Retrofit\Test\Functional;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use JMS\Serializer\SerializerBuilder;
use Mockery;
use PHPUnit_Framework_TestCase;
use Tebru\Retrofit\Adapter\RestAdapter;
use Tebru\Retrofit\Test\Functional\Mock\MockService;
use Tebru\Retrofit\Test\Functional\Mock\MockUser;

/**
 * Class ClientGenerationTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ClientGenerationTest extends PHPUnit_Framework_TestCase
{
    const BASE_URL = 'http://mockservice.com';

    public function testSimpleGet()
    {
        $this->createClient('GET', '/get')->simpleGet();
    }

    public function testSimplePost()
    {
        $this->createClient('POST', '/post')->simplePost();
    }

    public function testSimplePut()
    {
        $this->createClient('PUT', '/put')->simplePut();
    }

    public function testSimpleDelete()
    {
        $this->createClient('DELETE', '/delete')->simpleDelete();
    }

    public function testSimpleHead()
    {
        $this->createClient('HEAD', '/head')->simpleHead();
    }

    public function testSimpleOptions()
    {
        $this->createClient('OPTIONS', '/options')->simpleOptions();
    }

    public function testSimplePatch()
    {
        $this->createClient('PATCH', '/patch')->simplePatch();
    }

    public function testGetWithVar()
    {
        $this->createClient('GET', '/get/1')->getWithVar(1);
    }

    public function testGetWithQuery()
    {
        $this->createClient('GET', '/get', [], ['foo' => 'bar'])->getWithQuery();
    }

    public function testGetWithQueryDynamic()
    {
        $this->createClient('GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz'])->getWithQueryDynamic('buzz');
    }

    public function testCanChangeQueryVar()
    {
        $this->createClient('GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz'])->canChangeQueryVar('buzz');
    }

    public function testGetWithQueryMap()
    {
        $this->createClient('GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz', ['bing' => 'bong']])->getWithQueryMap('buzz', ['bing' => 'bong']);
    }

    public function testGetWithQueryMapNested()
    {
        $this->createClient('GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz', ['map' => ['bing' => 'bong'], 'bloop' => 'loop']])->getWithQueryMapNested('buzz', ['map' => ['bing' => 'bong'], 'bloop' => 'loop']);
    }

    public function testCanChangeQueryMapVar()
    {
        $this->createClient('GET', '/get', [], ['foo' => 'bar', 'baz' => 'buzz', ['bing' => 'bong']])->canChangeQueryMapVar('buzz', ['bing' => 'bong']);
    }

    public function testPostWithSimpleBody()
    {
        $this->createClient('POST', '/post', ['body' => ['foo' => 'bar']], [], [])->postWithSimpleBody(['foo' => 'bar']);
    }

    public function testCanChangeBodyVar()
    {
        $this->createClient('POST', '/post', ['body' => ['foo' => 'bar']], [], [])->canChangeBodyVar(['foo' => 'bar']);
    }

    public function testPostWithObjectBody()
    {
        $user = $this->getUser();
        $jsonUser = $this->serializeUser($user);
        $this->createClient('POST', '/post', ['body' => $jsonUser], [], [])->postWithObjectBody($user);
    }

    public function testPostWithPart()
    {
        $this->createClient('POST', '/post', ['body' => ['foo' => 'bar']], [], [])->postWithPart('bar');
    }

    public function testPostWithMultipleParts()
    {
        $this->createClient('POST', '/post', ['body' => ['foo' => 'bar', 'baz' => 'buzz']], [], [])->postWithMultipleParts('bar', 'buzz');
    }

    public function testCanChangePartVar()
    {
        $this->createClient('POST', '/post', ['body' => ['foo' => 'bar']], [], [])->canChangePartVar('bar');
    }

    public function testGetWithHeader()
    {
        $this->createClient('GET', '/get', [], [], ['foo' => 'bar'])->getWithHeader('bar');
    }

    public function testCanChangeHeaderVar()
    {
        $this->createClient('GET', '/get', [], [], ['foo' => 'bar'])->canChangeHeaderVar('bar');
    }

    public function testGetWithMultipleHeaders()
    {
        $this->createClient('GET', '/get', [], [], ['foo' => 'bar', 'baz' => 'buzz'])->getWithMultipleHeaders('bar', 'buzz');
    }

    public function testGetWithStaticHeaders()
    {
        $this->createClient('GET', '/get', [], [], ['Foo' => 'bar'])->getWithStaticHeaders();
    }

    public function testGetWithStaticHeadersList()
    {
        $this->createClient('GET', '/get', [], [], ['Foo' => 'bar', 'Baz' => 'buzz'])->getWithStaticHeadersList();
    }

    public function testRawReturn()
    {
        $user = $this->createClient('GET', '/get')->getRawReturn();
        $this->assertSame($this->serializeUser($this->getUser()), $user);
    }

    public function testArrayReturn()
    {
        $user = $this->createClient('GET', '/get')->getArrayReturn();
        $this->assertSame(['id' => 1, 'name' => 'Nate'], $user);
    }

    public function testDefaultReturnIsArray()
    {
        $user = $this->createClient('GET', '/get')->getDefaultReturnIsArray();
        $this->assertSame(['id' => 1, 'name' => 'Nate'], $user);
    }

    public function testDeserializedReturn()
    {
        $user = $this->createClient('GET', '/get')->getDeserializedReturn();
        $this->assertEquals($this->getUser(), $user);
    }

    /**
     * @param $method
     * @param $path
     * @param array $options
     * @param array $query
     * @param array $headers
     * @return MockService
     */
    private function createClient($method, $path, $options = [], $query = [], $headers = [])
    {
        $request = Mockery::mock(RequestInterface::class);
        $request->shouldReceive('setQuery')->times(1)->with($query)->andReturnNull();
        $request->shouldReceive('addHeaders')->times(1)->with($headers)->andReturnNull();

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

        return $restAdapter->create(MockService::class);
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

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Feature\Context;

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use PHPUnit_Framework_Assert as Assert;
use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\Http\Response;
use Tebru\Retrofit\HttpClient\Adapter\Guzzle\GuzzleV5ClientAdapter;
use Tebru\Retrofit\HttpClient\Adapter\Guzzle\GuzzleV6ClientAdapter;
use Tebru\Retrofit\Test\Mock\Api\MockApiResponse;
use Tebru\Retrofit\Test\Mock\Api\MockApiUser;
use Tebru\Retrofit\Test\Mock\Api\MockApiUserSerializable;
use Tebru\Retrofit\Test\Mock\Api\MockAvatar;
use Tebru\Retrofit\Test\Mock\Api\MockAvatarSerializable;
use Tebru\Retrofit\Test\Mock\ApiClient;

/**
 * Class FeatureContext
 *
 * @author Nate Brunette <n@tebru.net>
 */
class FeatureContext implements Context
{
    /**
     * @var array
     */
    private $response;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $queryParams = [];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $content = [];

    /**
     * @var array
     */
    private $files = [];

    /**
     * @var array
     */
    private $user = [];

    /**
     * @When /^I get a user$/
     */
    public function iGetAUser()
    {
        $this->setExpectations('GET', '/api/basic/user');
        $client = $this->getClient();
        $this->response = $client->getUser();
    }

    /**
     * @When /^I get a user by id$/
     */
    public function iGetAUserById()
    {
        $this->setExpectations('GET', '/api/basic/user/1');
        $client = $this->getClient();
        $this->response = $client->getUserById(1);
    }

    /**
     * @When /^I get a user by name$/
     */
    public function iGetAUserByName()
    {
        $this->setExpectations('GET', '/api/basic/user', ['name' => 'Nate']);
        $client = $this->getClient();
        $this->response = $client->getUserByQuery('Nate');
    }

    /**
     * @When /^I get a user by name and age$/
     */
    public function iGetAUserByNameAndAge()
    {
        $this->setExpectations('GET', '/api/basic/user', ['name' => 'Nate', 'age' => '21', 'enabled' => 'true']);
        $client = $this->getClient();
        $this->response = $client->getUserByMultipleQuery(['name' => 'Nate', 'age' => 21, 'enabled' => true]);
    }

    /**
     * @When I create a user from :type as :format
     */
    public function iCreateAUser($type, $format)
    {
        $body = null;
        switch ($type) {
            case 'array':
                $body = ['name' => 'Nate', 'age' => 21, 'enabled' => true];
                break;
            case 'object':
                $body = new MockApiUser('Nate', 21, true);
                break;
            case 'json_serializable':
                $body = new MockApiUserSerializable('Nate', 21, true);
                break;
        }

        $headers = [];
        $expectedBody = [];
        switch ($format) {
            case 'json':
                $expectedBody = ['name' => 'Nate', 'age' => 21, 'enabled' => true];
                $headers = $this->getHeaders('application/json');
                break;
            case 'formurlencoded':
                $expectedBody = ['name' => 'Nate', 'age' => '21', 'enabled' => 'true'];
                $headers = $this->getHeaders();
                break;
        }

        $this->setExpectations('POST', '/api/basic/user', [], $headers, $expectedBody);
        $client = $this->getClient();

        switch ($type) {
            case 'array':
                switch ($format) {
                    case 'json':
                        $this->response = $client->createUserArrayJson($body);
                        break;
                    case 'formurlencoded':
                        $this->response = $client->createUserArrayForm($body);
                        break;
                }
                break;
            case 'object':
                switch ($format) {
                    case 'json':
                        $this->response = $client->createUserObjectJson($body);
                        break;
                    case 'formurlencoded':
                        $this->response = $client->createUserObjectForm($body);
                        break;
                }
                break;
            case 'json_serializable':
                switch ($format) {
                    case 'json':
                        $this->response = $client->createUserJsonObjectJson($body);
                        break;
                    case 'formurlencoded':
                        $this->response = $client->createUserJsonObjectForm($body);
                        break;
                }
                break;
            case 'string':
                switch ($format) {
                    case 'json':
                        $body = json_encode(['name' => 'Nate', 'age' => 21, 'enabled' => true]);
                        $this->response = $client->createUserStringJson($body);
                        break;
                    case 'formurlencoded':
                        $body = http_build_query(['name' => 'Nate', 'age' => 21, 'enabled' => 'true']);
                        $this->response = $client->createUserStringForm($body);
                        break;
                }
                break;
            case 'parts':
                switch ($format) {
                    case 'json':
                        $this->response = $client->createUserPartsJson('Nate', 21, true);
                        break;
                    case 'formurlencoded':
                        $this->response = $client->createUserPartsForm('Nate', 21);
                        break;
                }
                break;
        }
    }

    /**
     * @When I upload an avatar as :format from :type
     */
    public function iUploadAnAvatar($format, $type)
    {
        $avatarPath = PROJECT_ROOT . '/tests/resources/images/avatar.png';
        $avatar = null;
        switch ($format) {
            case 'string':
                $avatar = $avatarPath;
                break;
            case 'resource':
                $avatar = fopen($avatarPath, 'r');
                break;
        }

        $body = null;
        switch ($type) {
            case 'array':
                $body = ['avatar' => $avatar];
                break;
            case 'object':
                $body = new MockAvatar($avatar);
                break;
            case 'json_serializable':
                $body = new MockAvatarSerializable($avatar);
                break;
            case 'parts':
                $body = $avatar;
                break;
            case 'string':
                if (is_string($avatar)) {
                    $avatar = fopen($avatar, 'r');
                }
                $body = new MultipartStream([['name' => 'avatar', 'contents' => $avatar]], 'fooboundary');
                break;
        }

        $headers = $this->getHeaders('multipart/form-data; boundary=fooboundary');
        $this->setExpectations('POST', '/api/basic/user-avatar', [], $headers, [], ['avatar' => ['name' => 'avatar.png']]);
        $client = $this->getClient();

        switch ($type) {
            case 'array':
                switch ($format) {
                    case 'string':
                        $this->response = $client->uploadAvatarArrayString($body);
                        break;
                    case 'resource':
                        $this->response = $client->uploadAvatarArrayResource($body);
                        break;
                }
                break;
            case 'object':
                switch ($format) {
                    case 'string':
                        $this->response = $client->uploadAvatarObjectString($body);
                        break;
                    case 'resource':
                        $this->response = $client->uploadAvatarObjectResource($body);
                        break;
                }
                break;
            case 'json_serializable':
                switch ($format) {
                    case 'string':
                        $this->response = $client->uploadAvatarJsonObjectString($body);
                        break;
                    case 'resource':
                        $this->response = $client->uploadAvatarJsonObjectResource($body);
                        break;
                }
                break;
            case 'parts':
                switch ($format) {
                    case 'string':
                        $this->response = $client->uploadAvatarPartsString($body);
                        break;
                    case 'resource':
                        $this->response = $client->uploadAvatarPartsResource($body);
                        break;
                }
                break;
            case 'string':
                switch ($format) {
                    case 'string':
                        $this->response = $client->uploadAvatarStringString($body);
                        break;
                    case 'resource':
                        $this->response = $client->uploadAvatarStringResource($body);
                        break;
                }
                break;
        }
    }

    /**
     * @When /^I set headers$/
     */
    public function iSetHeaders()
    {
        $headers = array_merge($this->getHeaders(), ['a' => ['1'], 'b' => ['2'], 'c' => ['3']]);
        $this->setExpectations('GET', '/api/basic/user', [], $headers);
        $client = $this->getClient();
        $this->response = $client->headers(3);
    }

    /**
     * @When /^I get a user and receive a mock api response$/
     */
    public function iGetAUserAndReceiveAMockApiResponse()
    {
        $this->setExpectations('GET', '/api/basic/user');
        $client = $this->getClient();
        $this->response = $client->getUserReturnMockApiResponse();
    }

    /**
     * @When /^I get a user and receive a retrofit response$/
     */
    public function iGetAUserAndReceiveARetrofitResponse()
    {
        $this->setExpectations('GET', '/api/basic/user');
        $client = $this->getClient();

        /** @var Response $response */
        $response = $client->getUserReturnRetrofitResponse();

        Assert::assertInstanceOf(Response::class, $response);

        $this->response = $response->body();
    }

    /**
     * @When /^I get a user and receive an array response$/
     */
    public function iGetAUserAndReceiveAnArrayResponse()
    {
        $this->setExpectations('GET', '/api/basic/user');
        $client = $this->getClient();
        $this->response = $client->getUserReturnArrayResponse();
    }

    /**
     * @When /^I get a user and receive a raw response$/
     */
    public function iGetAUserAndReceiveARawResponse()
    {
        $this->setExpectations('GET', '/api/basic/user');
        $client = $this->getClient();
        $response = $client->getUserReturnRawResponse();

        Assert::assertTrue(is_string($response));

        $this->response = json_decode($response, true);
    }

    /**
     * @Then /^The response validates$/
     */
    public function theResponseValidates()
    {
        if (is_array($this->response)) {
            $this->validateArrayResponse();
        }

        if ($this->response instanceof MockApiResponse) {
            $this->validateMockApiResponse();
        }
    }

    private function validateArrayResponse()
    {
        Assert::assertSame($this->method, $this->response['method']);
        Assert::assertSame($this->path, $this->response['path']);
        Assert::assertSame($this->queryParams, $this->response['query_params']);
        Assert::assertSame($this->content, $this->response['content']);
        Assert::assertSame($this->user, $this->response['user']);

        foreach ($this->headers as $key => $value) {
            Assert::assertSame($value, $this->response['headers'][$key]);
        }

        foreach ($this->files as $key => $value) {
            Assert::assertSame($this->files[$key]['name'], $this->response['files'][$key]['name']);
        }
    }

    private function validateMockApiResponse()
    {
        /** @var MockApiResponse $response */
        $response = $this->response;

        Assert::assertSame($this->method, $response->getMethod());
        Assert::assertSame($this->path, $response->getPath());
        Assert::assertSame($this->queryParams, $response->getQueryParams());
        Assert::assertSame($this->content, $response->getContent());
        Assert::assertSame($this->user, $response->getUser());

        foreach ($this->headers as $key => $value) {
            Assert::assertSame($value, $response->getHeaders()[$key]);
        }

        foreach ($this->files as $key => $value) {
            Assert::assertSame($this->files[$key]['name'], $response->getFiles()[$key]['name']);
        }
    }

    private function getHeaders($contentType = 'application/x-www-form-urlencoded')
    {
        return [
            'host' => ['127.0.0.1:8000'],
            'authorization' => ['Basic dXNlcjpwYXNzd29yZA=='],
            'content-type' => [$contentType],
            'php-auth-user' => ['user'],
            'php-auth-pw' => ['password'],
        ];
    }

    private function setExpectations(
        $method,
        $path,
        array $queryParams = [],
        array $headers = [],
        array $content = [],
        array $files = [],
        array $user = []
    ) {
        if (empty($headers)) {
            $headers = $this->getHeaders();
        }
        if (empty($user)) {
            $user = ['username' => 'user', 'password' => 'password'];
        }

        $this->method = $method;
        $this->path = $path;
        $this->queryParams = $queryParams;
        $this->headers = $headers;
        $this->content = $content;
        $this->files = $files;
        $this->user = $user;
    }

    /**
     * @return ApiClient
     * @throws RetrofitException
     */
    private function getClient()
    {
        $defaults = ['auth' => ['user', 'password'], 'exceptions' => false];
        $clientAdapter = (version_compare(Client::VERSION, '6', '<'))
            ? new GuzzleV5ClientAdapter(new Client(['defaults' => $defaults]))
            : new GuzzleV6ClientAdapter(new Client($defaults));

        $restAdapter = RestAdapter::builder()
            ->setBaseUrl('http://127.0.0.1:8000')
            ->setClientAdapter($clientAdapter)
            ->build();

        return $restAdapter->create(ApiClient::class);
    }
}

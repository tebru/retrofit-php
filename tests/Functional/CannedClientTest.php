<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mockable;

use Tebru\Retrofit\Adapter\Rest\RestAdapter;
use Tebru\Retrofit\Test\Mock\Canned\Post;
use Tebru\Retrofit\Test\Mock\CannedClient;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class CannedClientTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class CannedClientTest extends MockeryTestCase
{
    /**
     * @var CannedClient
     */
    private $client;

    private static $pathToJson;

    protected function setUp()
    {
        $restAdapter = RestAdapter::builder()
            ->setBaseUrl('http://localhost:5000')
            ->build();

        $this->client = $restAdapter->create(CannedClient::class);
        self::$pathToJson = __DIR__ . '/../canned';
    }

    public function testListPosts()
    {
        $response = $this->client->listPosts();
        $expected = json_encode(['data' => [['id' => 1, 'title' => 'Title 1'], ['id' => 2, 'title' => 'Title 2']]]);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testGetPostComments()
    {
        $response = $this->client->getPostComments(2);
        $expected = json_encode(['data' => [['text' => 'Comment 1']]]);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testListPostsSorted()
    {
        $response = $this->client->searchPosts('desc', ['search' => '1']);
        $expected = json_encode(['data' => [['id' => 1, 'title' => 'Title 1']]]);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testGetPost()
    {
        $response = $this->client->getPost(1);
        $expected = json_encode(['id' => 1, 'title' => 'Title 1']);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testCreatePost()
    {
        $response = $this->client->createPost(['title' => 'My Title']);
        $expected = json_encode(['id' => 3, 'title' => 'My Title']);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testCreatePostDeserialized()
    {
        $response = $this->client->createPostDeserialized(['title' => 'My Title']);
        $this->assertInstanceOf(Post::class, $response);
        $this->assertSame('My Title', $response->title);
        $this->assertNull($response->id);
    }

    public function testCreateAnotherPost()
    {
        $response = $this->client->createPost(['title' => 'My Other Title']);
        $expected = json_encode(['id' => 4, 'title' => 'My Other Title']);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testCreatePostFromObject()
    {
        $post = new Post();
        $post->title = 'My Title';
        $response = $this->client->createPostFromObject($post);
        $expected = json_encode(['id' => 3, 'title' => 'My Title']);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testUpdatePost()
    {
        $response = $this->client->updatePost(['id' => 3, 'title' => 'My New Title']);
        $expected = json_encode(['id' => 3, 'title' => 'My New Title']);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

    public function testUpdatePostFromParts()
    {
        $response = $this->client->updatePostFromParts(4, 'My New Other Title');
        $expected = json_encode(['id' => 4, 'title' => 'My New Other Title']);
        $this->assertJsonStringEqualsJsonString($expected, $response);
    }

}

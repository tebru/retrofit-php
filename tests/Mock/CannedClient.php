<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock;

use Tebru\Retrofit\Annotation as Rest;
use Tebru\Retrofit\Test\Mock\Canned\Post;

/**
 * Interface CannedClient
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Rest\Headers({
 *  "Content-Type: application/json"
 * })
 * @Rest\Returns("raw")
 */
interface CannedClient
{
    /**
     * @Rest\GET("/posts")
     */
    public function listPosts();

    /**
     * @Rest\GET("/posts/{id}/comments")
     */
    public function getPostComments($id);

    /**
     * @Rest\GET("/posts")
     * @Rest\Query("sort")
     */
    public function listPostsSorted($sort);

    /**
     * @Rest\GET("/posts")
     * @Rest\QueryMap("queries")
     */
    public function listPostsSortedMap(array $queries);

    /**
     * @Rest\GET("/posts?foo=bar")
     * @Rest\Query("sort")
     * @Rest\QueryMap("queries")
     */
    public function searchPosts($sort, array $queries);

    /**
     * @Rest\GET("/posts/{id}")
     */
    public function getPost($id);

    /**
     * @Rest\POST("/posts")
     * @Rest\Body("post")
     * @Rest\JsonBody()
     */
    public function createPost(array $post);

    /**
     * @Rest\POST("/posts")
     * @Rest\Body("post")
     * @Rest\JsonBody()
     * @Rest\Serializer\DeserializationContext(groups={"detail"})
     * @Rest\Returns("Tebru\Retrofit\Test\Mock\Canned\Post")
     */
    public function createPostDeserialized(array $post);

    /**
     * @Rest\POST("/posts")
     * @Rest\Body("post")
     * @Rest\JsonBody()
     */
    public function createPostFromObject(Post $post);

    /**
     * @Rest\PUT("/posts")
     * @Rest\Body("post")
     * @Rest\JsonBody()
     */
    public function updatePost(array $post);

    /**
     * @Rest\PUT("/posts")
     * @Rest\Part("id")
     * @Rest\Part("title")
     * @Rest\JsonBody()
     */
    public function updatePostFromParts($id, $title);
}

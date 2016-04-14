<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock;

use Tebru\Retrofit\Annotation\BaseUrl;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;
use Tebru\Retrofit\Http\AsyncAware;
use Tebru\Retrofit\Http\Callback;
use Tebru\Retrofit\Test\Mock\Api\MockApiUser;
use Tebru\Retrofit\Test\Mock\Api\MockApiUserSerializable;
use Tebru\Retrofit\Test\Mock\Api\MockAvatar;
use Tebru\Retrofit\Test\Mock\Api\MockAvatarSerializable;

/**
 * Interface ApiClient
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Headers({"Accept-Content: application/json"})
 */
interface ApiClient extends AsyncAware
{
    /**
     * @GET("/api/basic/user")
     */
    public function getUser();

    /**
     * @GET("/api/basic/user/{id}")
     */
    public function getUserById($id);

    /**
     * @GET("/api/basic/user")
     * @Query("name")
     */
    public function getUserByQuery($name);

    /**
     * @GET("/api/basic/user")
     * @QueryMap("queries")
     */
    public function getUserByMultipleQuery(array $queries);

    /**
     * @GET("/api/basic/user?limit=1")
     * @Query("name")
     */
    public function getUserByQueryLimit($name);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @JsonBody()
     */
    public function createUserArrayJson(array $user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @FormUrlEncoded()
     */
    public function createUserArrayForm(array $user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @JsonBody()
     */
    public function createUserObjectJson(MockApiUser $user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @JsonBody()
     */
    public function createUserObjectJsonOptional(MockApiUser $user = null);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @FormUrlEncoded()
     */
    public function createUserObjectForm(MockApiUser $user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @JsonBody()
     */
    public function createUserJsonObjectJson(MockApiUserSerializable $user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @FormUrlEncoded()
     */
    public function createUserJsonObjectForm(MockApiUserSerializable $user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @JsonBody()
     */
    public function createUserStringJson($user);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @FormUrlEncoded()
     */
    public function createUserStringForm($user);

    /**
     * @POST("/api/basic/user")
     * @Part("name")
     * @Part("age")
     * @Part("enabled")
     * @JsonBody()
     */
    public function createUserPartsJson($name, $age, $enabled = false);

    /**
     * @POST("/api/basic/user")
     * @Part("name")
     * @Part("age")
     * @Part("enabled")
     * @FormUrlEncoded()
     */
    public function createUserPartsForm($name, $age, $enabled = true);

    /**
     * @POST("/api/basic/user")
     * @Body("user")
     * @SerializationContext(serializeNull=true, enableMaxDepthChecks=true)
     * @JsonBody()
     */
    public function createUserWithoutAllFields(MockApiUser $user);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarArrayString(array $avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarArrayResource(array $avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarObjectString(MockAvatar $avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarObjectResource(MockAvatar $avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarJsonObjectString(MockAvatarSerializable $avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarJsonObjectResource(MockAvatarSerializable $avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Part("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarPartsString($avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Part("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarPartsResource($avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarStringString($avatar);

    /**
     * @POST("/api/basic/user-avatar")
     * @Body("avatar")
     * @Multipart(boundary="fooboundary")
     */
    public function uploadAvatarStringResource($avatar);

    /**
     * @GET("/api/basic/user")
     * @Header("Accept-Language", var="language")
     */
    public function getUserWithFrenchLanguage($language);

    /**
     * @GET("/api/basic/user")
     * @Returns("Tebru\Retrofit\Test\Mock\Api\MockApiResponse")
     */
    public function getUserReturnMockApiResponse();

    /**
     * @GET("/api/basic/user")
     * @Returns("Response")
     * @ResponseType("Tebru\Retrofit\Test\Mock\Api\MockApiResponse")
     */
    public function getUserReturnRetrofitResponse();

    /**
     * @GET("/api/basic/user")
     * @Returns("array")
     */
    public function getUserReturnArrayResponse();

    /**
     * @GET("/api/basic/user")
     * @Returns("raw")
     */
    public function getUserReturnRawResponse();

    /**
     * @BaseUrl("baseUrl")
     * @GET("")
     */
    public function getUserWithBaseUrl($baseUrl);

    /**
     * @GET("/api/basic/user")
     */
    public function getUserAsync(Callback $callback);
}

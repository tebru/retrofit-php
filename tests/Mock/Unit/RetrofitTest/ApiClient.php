<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\DELETE;
use Tebru\Retrofit\Annotation\Field;
use Tebru\Retrofit\Annotation\FieldMap;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HEAD;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\HeaderMap;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\OPTIONS;
use Tebru\Retrofit\Annotation\Part;
use Tebru\Retrofit\Annotation\PartMap;
use Tebru\Retrofit\Annotation\PATCH;
use Tebru\Retrofit\Annotation\Path;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\PUT;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Annotation\QueryName;
use Tebru\Retrofit\Annotation\REQUEST;
use Tebru\Retrofit\Annotation\ResponseBody;
use Tebru\Retrofit\Annotation\Url;
use Tebru\Retrofit\Call;
use Tebru\Retrofit\Http\MultipartBody;

/**
 * Interface ApiClient
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ApiClient
{
    /**
     * @GET("/")
     */
    public function get(): Call;

    /**
     * @OPTIONS("/{my-path}?q=test")
     * @Url("newUrl")
     * @Path("my-path", var="path")
     * @Query("query[]", var="query1")
     * @QueryName("query2")
     * @QueryMap("queryMap")
     */
    public function uri(string $newUrl, array $queryMap, array $query1, bool $query2, string $path): Call;

    /**
     * @HEAD("/")
     * @Headers({
     *     "X-Foo: bar",
     *     "X-Baz: qux",
     *     "X-Header: first"
     * })
     * @Header("X-Header", var="header1")
     * @Header("header2")
     * @HeaderMap("headerMap")
     */
    public function headers(array $headerMap, array $header1, int $header2): Call;

    /**
     * @POST("/")
     */
    public function postWithoutBody(): Call;

    /**
     * @PUT("/")
     * @Body("requestBody")
     * @ResponseBody("Tebru\Retrofit\Test\Mock\Unit\RetrofitTest\RetrofitTestResponseBodyMock")
     */
    public function body(RetrofitTestRequestBodyMock $requestBody): Call;

    /**
     * @PATCH("/")
     * @Field("field1")
     * @Field("field2")
     * @Field("field3", encoded=true)
     * @FieldMap("fieldMap")
     */
    public function field(float $field1, bool $field2, string $field3, array $fieldMap): Call;

    /**
     * @REQUEST("/", type="FOO", body=true)
     * @Part("part1")
     * @Part("part2")
     * @PartMap("partMap")
     */
    public function part(RetrofitTestRequestBodyMock $part1, MultipartBody $part2, array $partMap): Call;

    /**
     * @GET("/")
     */
    public function callAdapter(): RetrofitTestAdaptedCallMock;

    /**
     * @DELETE("/")
     * @RetrofitTestCustomAnnotation
     */
    public function customAnnotation(): Call;
}

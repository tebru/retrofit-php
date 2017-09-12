<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\ProxyFactoryTest;

use stdClass;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Field;
use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\Path;
use Tebru\Retrofit\Annotation\POST;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Call;

/**
 * Interface PFTCTestCreateClientFail
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface PFTCTestCreateClientFail
{
    /**
     * @POST("/foo/path")
     * @Path("path")
     * @Body("body")
     * @Query("query")
     * @Field("field", var="fields")
     *
     * @param string $path
     * @param stdClass $body
     * @param string $query
     * @param string[] $fields
     * @return Call
     */
    public function simple(string $path, stdClass $body, string &$query = 'foo', string ...$fields): Call;

    /**
     * @GET("/")
     *
     * @return Call
     */
    public static function static(): Call;
}


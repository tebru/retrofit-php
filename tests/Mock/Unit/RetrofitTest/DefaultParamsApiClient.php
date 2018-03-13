<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\HeaderMap;
use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Call;

/**
 * Interface DefaultParamsApiClient
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface DefaultParamsApiClient
{
    /**
     * @GET("/")
     * @Query("string")
     * @Query("bool")
     * @Query("int")
     * @Query("float")
     * @QueryMap("client")
     * @HeaderMap("array")
     *
     * @param string $string
     * @param bool $bool
     * @param int $int
     * @param float $float
     * @param array $array
     * @param ApiClient|null $client
     * @return Call
     */
    public function getWithDefaults(
        ?string $string = 'test',
        ?bool $bool = true,
        ?int $int = 1,
        ?float $float = 3.2,
        ?array $array = ['test' => ['value']],
        ?ApiClient $client = null
    ): Call;
}

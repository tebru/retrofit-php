<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\Retrofit\Annotation\GET;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Call;

/**
 * Interface InvalidSyntaxApiClient
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface InvalidSyntaxApiClient
{
    /**
     * @GET("/")
     * @Headers({"asdf": "asdf"})
     */
    public function get(): Call;
}

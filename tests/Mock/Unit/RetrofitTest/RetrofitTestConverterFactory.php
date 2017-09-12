<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\RetrofitTest;

use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\ConverterFactory;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ResponseBodyConverter;
use Tebru\Retrofit\StringConverter;

/**
 * Class RetrofitTestConverterFactory
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitTestConverterFactory implements ConverterFactory
{
    /**
     * Return a [@see ResponseBodyConverter] or null
     *
     * @param TypeToken $type
     * @return null|ResponseBodyConverter
     */
    public function responseBodyConverter(TypeToken $type): ?ResponseBodyConverter
    {
        return new RetrofitTestResponseBodyConverter();
    }

    /**
     * Return a [@see RequestBodyConverter] or null
     *
     * @param TypeToken $type
     * @return null|RequestBodyConverter
     */
    public function requestBodyConverter(TypeToken $type): ?RequestBodyConverter
    {
        return new RetrofitTestRequestBodyConverter();
    }

    /**
     * Return a [@see StringConverter] or null
     *
     * @param TypeToken $type
     * @return null|StringConverter
     */
    public function stringConverter(TypeToken $type): ?StringConverter
    {
        return null;
    }
}

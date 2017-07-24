<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit;

use Tebru\PhpType\TypeToken;

/**
 * Interface ConverterFactory
 *
 * Implementors should return implementations of converters for the types
 * that are supported, and null if the type is not supported.
 *
 * For example, if a converter does not convert types to strings, just return
 * null from the stringConverter() method.
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface ConverterFactory
{
    /**
     * Return a [@see ResponseBodyConverter] or null
     *
     * @param TypeToken $type
     * @return null|ResponseBodyConverter
     */
    public function responseBodyConverter(TypeToken $type): ?ResponseBodyConverter;

    /**
     * Return a [@see RequestBodyConverter] or null
     *
     * @param TypeToken $type
     * @return null|RequestBodyConverter
     */
    public function requestBodyConverter(TypeToken $type): ?RequestBodyConverter;

    /**
     * Return a [@see StringConverter] or null
     *
     * @param TypeToken $type
     * @return null|StringConverter
     */
    public function stringConverter(TypeToken $type): ?StringConverter;
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\Converter;

use Psr\Http\Message\StreamInterface;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\ConverterFactory;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ResponseBodyConverter;
use Tebru\Retrofit\StringConverter;

/**
 * Class DefaultConverterFactory
 *
 * Creates default converters
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class DefaultConverterFactory implements ConverterFactory
{
    /**
     * Return default converter if type is a stream
     *
     * @param TypeToken $type
     * @return null|ResponseBodyConverter
     */
    public function responseBodyConverter(TypeToken $type): ?ResponseBodyConverter
    {
        if (!$type->isA(StreamInterface::class)) {
            return null;
        }

        return new DefaultResponseBodyConverter();
    }

    /**
     * Return default converter if type is a stream
     *
     * @param TypeToken $type
     * @return null|RequestBodyConverter
     */
    public function requestBodyConverter(TypeToken $type): ?RequestBodyConverter
    {
        if (!$type->isA(StreamInterface::class)) {
            return null;
        }

        return new DefaultRequestBodyConverter();
    }

    /**
     * Return default string converter for any type
     *
     * If the type is a string already, use a converter that doesn't do
     * any type checking.
     *
     * @param TypeToken $type
     * @return null|StringConverter
     */
    public function stringConverter(TypeToken $type): ?StringConverter
    {
        if ($type->isString()) {
            return new NoopStringConverter();
        }

        return new DefaultStringConverter();
    }
}

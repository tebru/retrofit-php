<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Test\Mock\Unit\Internal\ServiceMethod\ServiceMethodFactoryTest;

use Psr\Http\Message\StreamInterface;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\ConverterFactory;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ResponseBodyConverter;
use Tebru\Retrofit\StringConverter;

/**
 * Class ServiceMethodFactorTestConverter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ServiceMethodFactoryTestConverterFactory implements ConverterFactory
{
    /**
     * Return a [@see ResponseBodyConverter] or null
     *
     * @param TypeToken $type
     * @return null|ResponseBodyConverter
     */
    public function responseBodyConverter(TypeToken $type): ?ResponseBodyConverter
    {
        return new class implements ResponseBodyConverter {
            public function convert(StreamInterface $value)
            {
                return $value;
            }
        };
    }

    /**
     * Return a [@see RequestBodyConverter] or null
     *
     * @param TypeToken $type
     * @return null|RequestBodyConverter
     */
    public function requestBodyConverter(TypeToken $type): ?RequestBodyConverter
    {
        return new class implements RequestBodyConverter {
            public function convert($value): StreamInterface
            {
                return $value;
            }
        };
    }

    /**
     * Return a [@see StringConverter] or null
     *
     * @param TypeToken $type
     * @return null|StringConverter
     */
    public function stringConverter(TypeToken $type): ?StringConverter
    {
        return new class implements StringConverter {
            public function convert($value): string
            {
                return (string)$value;
            }
        };
    }
}

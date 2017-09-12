<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\Converter;

use LogicException;
use Tebru\PhpType\TypeToken;
use Tebru\Retrofit\ConverterFactory;
use Tebru\Retrofit\RequestBodyConverter;
use Tebru\Retrofit\ResponseBodyConverter;
use Tebru\Retrofit\StringConverter;

/**
 * Class ConverterProvider
 *
 * Returns a [@see ConverterFactory] that matches the provided type
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class ConverterProvider
{
    /**
     * A cache of [@see ResponseBodyConverter]'s
     *
     * @var ResponseBodyConverter[]
     */
    private $responseBodyConverters = [];

    /**
     * A cache of [@see RequestBodyConverter]'s
     *
     * @var RequestBodyConverter[]
     */
    private $requestBodyConverters = [];

    /**
     * A cache of [@see StringConverter]'s
     *
     * @var StringConverter[]
     */
    private $stringConverters = [];

    /**
     * An array of [@see ConverterFactory]'s
     *
     * @var ConverterFactory[]
     */
    private $converterFactories;

    /**
     * Constructor
     *
     * @param ConverterFactory[] $factories
     */
    public function __construct(array $factories)
    {
        $this->converterFactories = array_values($factories);
    }

    /**
     * Get a response body converter for type
     *
     * @param TypeToken $type
     * @return ResponseBodyConverter
     * @throws LogicException
     */
    public function getResponseBodyConverter(TypeToken $type): ResponseBodyConverter
    {
        $key = (string)$type;
        if (isset($this->responseBodyConverters[$key])) {
            return $this->responseBodyConverters[$key];
        }

        foreach ($this->converterFactories as $converterFactory) {
            $converter = $converterFactory->responseBodyConverter($type);
            if ($converter === null) {
                continue;
            }

            $this->responseBodyConverters[$key] = $converter;

            return $converter;
        }

        throw new LogicException(sprintf(
            'Retrofit: Could not get response body converter for type %s',
            $type
        ));
    }

    /**
     * Get a request body converter for type
     *
     * @param TypeToken $type
     * @return RequestBodyConverter
     * @throws \LogicException
     */
    public function getRequestBodyConverter(TypeToken $type): RequestBodyConverter
    {
        $key = (string)$type;
        if (isset($this->requestBodyConverters[$key])) {
            return $this->requestBodyConverters[$key];
        }

        foreach ($this->converterFactories as $converterFactory) {
            $converter = $converterFactory->requestBodyConverter($type);
            if ($converter === null) {
                continue;
            }

            $this->requestBodyConverters[$key] = $converter;

            return $converter;
        }

        throw new LogicException(sprintf(
            'Retrofit: Could not get request body converter for type %s',
            $type
        ));
    }

    /**
     * Get a string converter for type
     *
     * @param TypeToken $type
     * @return StringConverter
     * @throws \LogicException
     */
    public function getStringConverter(TypeToken $type): StringConverter
    {
        $key = (string)$type;
        if (isset($this->stringConverters[$key])) {
            return $this->stringConverters[$key];
        }

        foreach ($this->converterFactories as $converterFactory) {
            $converter = $converterFactory->stringConverter($type);
            if ($converter === null) {
                continue;
            }

            $this->stringConverters[$key] = $converter;

            return $converter;
        }

        throw new LogicException(sprintf(
            'Retrofit: Could not get string converter for type %s',
            $type
        ));
    }
}

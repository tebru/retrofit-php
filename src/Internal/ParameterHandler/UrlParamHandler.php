<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ParameterHandler;

use RuntimeException;
use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\StringConverter;

/**
 * Class UrlParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class UrlParamHandler extends AbstractParameterHandler
{
    /**
     * @var StringConverter
     */
    private $converter;

    /**
     * Constructor
     *
     * @param StringConverter $converter
     */
    public function __construct(StringConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Set a value to the [@see RequestBuilder] for parameter type
     *
     * @param RequestBuilder $requestBuilder
     * @param mixed $value
     * @return void
     * @throws \RuntimeException
     */
    public function apply(RequestBuilder $requestBuilder, $value): void
    {
        if ($value === null) {
            throw new RuntimeException('Url parameters cannot be null');
        }

        $requestBuilder->setBaseUrl($this->converter->convert($value));
    }
}

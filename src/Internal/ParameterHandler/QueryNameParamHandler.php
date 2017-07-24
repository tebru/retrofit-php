<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ParameterHandler;

use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\StringConverter;

/**
 * Class QueryNameParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class QueryNameParamHandler extends AbstractParameterHandler
{
    /**
     * @var StringConverter
     */
    private $converter;

    /**
     * @var bool
     */
    private $encoded;

    /**
     * Constructor
     *
     * @param StringConverter $converter
     * @param bool $encoded
     */
    public function __construct(StringConverter $converter, bool $encoded)
    {
        $this->converter = $converter;
        $this->encoded = $encoded;
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
        foreach ($this->getListValues($value) as $element) {
            $requestBuilder->addQueryName($this->converter->convert($element), $this->encoded);
        }
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ParameterHandler;

use Iterator;
use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\StringConverter;

/**
 * Class QueryMapParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class QueryMapParamHandler extends AbstractParameterHandler
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
     * @param array|Iterator $map
     * @return void
     * @throws \RuntimeException
     */
    public function apply(RequestBuilder $requestBuilder, $map): void
    {
        if ($map === null) {
            return;
        }

        foreach ($map as $name => $value) {
            foreach ($this->getListValues($value) as $element) {
                $requestBuilder->addQuery($name, $this->converter->convert($element), $this->encoded);
            }
        }
    }
}

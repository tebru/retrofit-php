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
 * Class HeaderMapParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class HeaderMapParamHandler extends AbstractParameterHandler
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
     * @param array|Iterator $map
     * @return void
     * @throws \RuntimeException
     */
    public function apply(RequestBuilder $requestBuilder, $map): void
    {
        foreach ($map as $name => $value) {
            foreach ($this->getListValues($value) as $element) {
                $requestBuilder->addHeader($name, $this->converter->convert($element));
            }
        }
    }
}

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
 * Class HeaderParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class HeaderParamHandler extends AbstractParameterHandler
{
    /**
     * @var StringConverter
     */
    private $converter;

    /**
     * @var string
     */
    private $name;

    /**
     * Constructor
     *
     * @param StringConverter $converter
     * @param string $name
     */
    public function __construct(StringConverter $converter, string $name)
    {
        $this->converter = $converter;
        $this->name = $name;
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
            $requestBuilder->addHeader($this->name, $this->converter->convert($element));
        }
    }
}

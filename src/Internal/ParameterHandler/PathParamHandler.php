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
 * Class PathParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class PathParamHandler extends AbstractParameterHandler
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
        if ($value === null) {
            throw new RuntimeException('Path parameters cannot be null');
        }

        $requestBuilder->replacePath($this->name, $this->converter->convert($value));
    }
}

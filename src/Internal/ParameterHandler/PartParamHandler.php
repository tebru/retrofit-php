<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ParameterHandler;

use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\RequestBodyConverter;

/**
 * Class PartParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class PartParamHandler extends AbstractParameterHandler
{
    /**
     * @var RequestBodyConverter
     */
    private $converter;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $encoding;

    /**
     * Constructor
     *
     * @param RequestBodyConverter $converter
     * @param string $name
     * @param string $encoding
     */
    public function __construct(RequestBodyConverter $converter, string $name, string $encoding)
    {
        $this->converter = $converter;
        $this->name = $name;
        $this->encoding = $encoding;
    }

    /**
     * Set a value to the [@see RequestBuilder] for parameter type
     *
     * @param RequestBuilder $requestBuilder
     * @param mixed $value
     * @return void
     */
    public function apply(RequestBuilder $requestBuilder, $value): void
    {
        if ($value === null) {
            return;
        }

        $this->handlePart($requestBuilder, $this->converter, $this->name, $value, $this->encoding);
    }
}

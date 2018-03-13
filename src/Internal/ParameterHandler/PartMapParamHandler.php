<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ParameterHandler;

use Iterator;
use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\RequestBodyConverter;

/**
 * Class PartMapParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class PartMapParamHandler extends AbstractParameterHandler
{
    /**
     * @var RequestBodyConverter
     */
    private $converter;

    /**
     * @var string
     */
    private $encoding;

    /**
     * Constructor
     *
     * @param RequestBodyConverter $converter
     * @param string $encoding
     */
    public function __construct(RequestBodyConverter $converter, string $encoding)
    {
        $this->converter = $converter;
        $this->encoding = $encoding;
    }

    /**
     * Set a value to the [@see RequestBuilder] for parameter type
     *
     * @param RequestBuilder $requestBuilder
     * @param array|Iterator $map
     * @return void
     */
    public function apply(RequestBuilder $requestBuilder, $map): void
    {
        if ($map === null) {
            return;
        }

        foreach ($map as $name => $value) {
            $this->handlePart($requestBuilder, $this->converter, $name, $value, $this->encoding);
        }
    }
}

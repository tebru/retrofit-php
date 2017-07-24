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
 * Class BodyParamHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
final class BodyParamHandler extends AbstractParameterHandler
{
    /**
     * @var RequestBodyConverter
     */
    private $converter;

    /**
     * Constructor
     *
     * @param RequestBodyConverter $converter
     */
    public function __construct(RequestBodyConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Converts the value to a stream, then sets the body to the request builder
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

        $requestBuilder->setBody($this->converter->convert($value));
    }
}

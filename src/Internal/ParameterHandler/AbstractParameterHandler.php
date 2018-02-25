<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Internal\ParameterHandler;

use Generator;
use RuntimeException;
use Tebru\Retrofit\Http\MultipartBody;
use Tebru\Retrofit\ParameterHandler;
use Tebru\Retrofit\Internal\RequestBuilder;
use Tebru\Retrofit\RequestBodyConverter;

/**
 * Class AbstractFieldHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
abstract class AbstractParameterHandler implements ParameterHandler
{
    private const HEADER_CON_TRANS_ENC = 'Content-Transfer-Encoding';

    /**
     * Convert a value to a generator
     *
     * This method is used when a value can optionally be an array and each element in the
     * array should be processed the same way.
     *
     * @param array|mixed $list
     * @return Generator
     * @throws \RuntimeException
     */
    protected function getListValues($list): Generator
    {
        foreach ((array)$list as $key => $element) {
            if (!\is_int($key)) {
                throw new RuntimeException('Retrofit: Array value must use numeric keys');
            }

            yield $element;
        }
    }

    /**
     * Handle Part or PartMap annotations
     *
     * This could use a simple method using name and value, or if a [@see MultipartBody] is passed in as the
     * value, then a filename and additional headers could be set as well.
     *
     * @param RequestBuilder $requestBuilder
     * @param RequestBodyConverter $converter
     * @param string $name
     * @param mixed $value
     * @param string $encoding
     * @return void
     */
    protected function handlePart(
        RequestBuilder $requestBuilder,
        RequestBodyConverter $converter,
        string $name,
        $value,
        string $encoding
    ): void {
        if ($value === null) {
            return;
        }

        // if not a MultipartBody, only set name, contents, and content header
        if (!$value instanceof MultipartBody) {
            $requestBuilder->addPart($name, $converter->convert($value), [self::HEADER_CON_TRANS_ENC => $encoding]);
            return;
        }

        $headers = $value->getHeaders();
        if (!isset($headers[self::HEADER_CON_TRANS_ENC])) {
            $headers[self::HEADER_CON_TRANS_ENC] = $encoding;
        }

        $requestBuilder->addPart($value->getName(), $value->getContents(), $headers, $value->getFilename());
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use RuntimeException;
use Tebru;
use Tebru\AnnotationReader\AbstractAnnotation;

/**
 * Adds headers statically supplied in the value.
 * 
 *     @Headers("Cache-Control: max-age=640000")
 *     @Headers({
 *         "X-Foo: Bar",
 *         "X-Ping: Pong"
 *     })
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Headers extends AbstractAnnotation
{
    /**
     * Initialize annotation data
     *
     * @throws \RuntimeException
     */
    protected function init(): void
    {
        // loop through each string and break on ':'
        foreach ((array)$this->getValue() as $header) {
            $position = strpos($header, ':');

            if ($position === false) {
                throw new RuntimeException('Retrofit: Header in an incorrect format.  Expected "Name: value"');
            }

            $name = trim(substr($header, 0, $position));
            $value = trim(substr($header, $position + 1));

            $this->value[$name] = (string)$value;
        }
    }
}

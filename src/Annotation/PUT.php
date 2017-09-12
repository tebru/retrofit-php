<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\AnnotationReader\AbstractAnnotation;

/**
 * Defines an HTTP PUT request type to a REST path relative to base URL.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class PUT extends AbstractAnnotation implements HttpRequest
{
    /**
     * Returns the type of the annotation (get, post, put, etc)
     *
     * @return string
     */
    public function getType(): string
    {
        return 'put';
    }

    /**
     * Returns true if the request type contains a body
     *
     * @return bool
     */
    public function hasBody(): bool
    {
        return true;
    }
}

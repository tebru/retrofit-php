<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru\AnnotationReader\AbstractAnnotation;

/**
 * Defines an HTTP request type to a REST path relative to base URL.
 *
 * This is a generic annotation that lets you define an http request
 * outside the standard GET, POST, etc
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class REQUEST extends AbstractAnnotation implements HttpRequest
{
    /**
     * The request method
     *
     * @var string
     */
    private $type;

    /**
     * If the request contains a body
     *
     * @var bool
     */
    private $body;

    /**
     * Initialize annotation data
     */
    protected function init(): void
    {
        $this->assertKey('type');

        $this->type = $this->data['type'];
        $this->body = $this->data['body'] ?? false;
    }


    /**
     * Returns the type of the annotation (get, post, put, etc)
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns true if the request type contains a body
     *
     * @return bool
     */
    public function hasBody(): bool
    {
        return $this->body;
    }
}

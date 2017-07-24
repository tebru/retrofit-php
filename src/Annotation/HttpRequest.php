<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

declare(strict_types=1);

namespace Tebru\Retrofit\Annotation;

use Tebru;

/**
 * Interface for the different http request annotations (e.g. @GET, @POST)
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface HttpRequest
{
    /**
     * Returns the type of the annotation (get, post, put, etc)
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Returns true if the request type contains a body
     *
     * @return bool
     */
    public function hasBody(): bool;

    /**
     * Get the url value
     *
     * @return mixed
     */
    public function getValue();
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;

/**
 * Class HeaderHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeaderHandler implements Handler
{
    /**
     * Create header
     *
     * @param HandlerContext $context
     * @return null
     */
    public function __invoke(HandlerContext $context)
    {
        $varHeaders = $context->annotations()->getHeaders();
        $staticHeaders = $context->annotations()->getStaticHeaders();

        $headers = $this->getContentTypeHeader($context);

        if (null !== $staticHeaders) {
            $headers = array_merge($headers, $staticHeaders);
        }

        if (null !== $varHeaders) {
            $headers = array_merge($headers, $varHeaders);
        }

        $context->body()->add('$headers = %s;', $context->printer()->printArray($headers));
    }

    /**
     * Get content type and set to header
     *
     * @param HandlerContext $context
     * @return array
     */
    private function getContentTypeHeader(HandlerContext $context)
    {
        if ($context->annotations()->isJsonEncoded()) {
            return ['Content-Type' => 'application/json'];
        }

        if ($context->annotations()->isFormUrlEncoded()) {
            return ['Content-Type' => 'application/x-www-form-urlencoded'];
        }

        if ($context->annotations()->isMultipart()) {
            return ['Content-Type' => 'multipart/form-data; boundary=' . $context->annotations()->getMultipartBoundary()];
        }

        $contentType = ['Content-Type' => 'application/x-www-form-urlencoded'];

        if (!$context->annotations()->hasBody()) {
            return $contentType;
        }

        trigger_error('Retrofit Deprecation: The default content type is changing in the next'
            . ' major version of Retrofit from application/x-www-form-urlencoded'
            . ' to application/json.  In order to ensure a clean upgrade, make'
            . ' sure you specify a specific content type with the proper annotation',
            E_USER_DEPRECATED
        );

        return $contentType;
    }
}

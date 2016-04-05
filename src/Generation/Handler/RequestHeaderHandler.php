<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\FormUrlEncoded;
use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;
use Tebru\Retrofit\Annotation\Part;

/**
 * Class RequestHeaderHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestHeaderHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        $headers = [];
        $headers = $this->setStaticHeaders($headers);
        $headers = $this->setHeaders($headers);
        $headers = $this->setContentTypeHeader($headers);

        $this->methodBodyBuilder->setHeaders($headers);
    }

    /**
     * Set request headers if annotation exists
     *
     * @param array $headers
     * @return array
     */
    private function setHeaders(array $headers)
    {
        if (!$this->annotations->exists(Header::NAME)) {

            return $headers;
        }

        /** @var Header $headerAnnotation */
        foreach ($this->annotations->get(Header::NAME) as $headerAnnotation) {
            $headers[$headerAnnotation->getRequestKey()] = $headerAnnotation->getVariable();
        }

        return $headers;
    }

    /**
     * Set request headers from list if annotation exists
     *
     * @param array $headers
     * @return array
     */
    private function setStaticHeaders(array $headers)
    {
        if (!$this->annotations->exists(Headers::NAME)) {
            return $headers;
        }


        /** @var Headers $headersAnnotation */
        $headersAnnotation = $this->annotations->get(Headers::NAME);
        foreach ($headersAnnotation->getHeaders() as $key => $value) {
            $headers[$key] = $value;
        }

        return $headers;
    }

    /**
     * Add a content type header
     *
     * @param array $headers
     * @return array
     */
    private function setContentTypeHeader(array $headers)
    {
        if ($this->annotations->exists(JsonBody::NAME)) {
            $this->methodBodyBuilder->setJsonEncode(true);
            $headers['Content-Type'] = 'application/json';

            return $headers;
        }

        if ($this->annotations->exists(FormUrlEncoded::NAME)) {
            $this->methodBodyBuilder->setFormUrlEncoded(true);
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';

            return $headers;
        }

        if ($this->annotations->exists(Multipart::NAME)) {
            $this->methodBodyBuilder->setMultipartEncoded(true);

            /** @var Multipart $annotation */
            $annotation = $this->annotations->get(Multipart::NAME);
            $boundary = $annotation->getBoundary() ? $annotation->getBoundary() : null;
            $this->methodBodyBuilder->setBoundaryId($boundary);

            $headers['Content-Type'] = 'multipart/form-data; boundary=' . $boundary;

            return $headers;
        }

        // if there is a body, display warning
        if ($this->annotations->exists(Body::NAME) || $this->annotations->exists(Part::NAME)) {
            trigger_error('Retrofit Deprecation: The default content type is changing in the next'
                . ' major version of Retrofit from application/x-www-form-urlencoded'
                . ' to application/json.  In order to ensure a clean upgrade, make'
                . ' sure you specify a specific content type with the proper annotation',
                E_USER_DEPRECATED
            );
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd

        $this->methodBodyBuilder->setFormUrlEncoded(true);
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';

        return $headers;
    }
}

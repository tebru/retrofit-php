<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;

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
        $headers = $this->setJsonHeader($headers);

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
     * Add a content type header if it's a json request
     *
     * @param array $headers
     * @return array
     */
    private function setJsonHeader(array $headers)
    {
        if (!$this->annotations->exists(JsonBody::NAME)) {
            return $headers;
        }

        $headers['Content-Type'] = 'application/json';

        return $headers;
    }
}

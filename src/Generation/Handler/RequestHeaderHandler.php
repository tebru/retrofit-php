<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Annotation\Headers;
use Tebru\Retrofit\Annotation\JsonBody;
use Tebru\Retrofit\Annotation\Multipart;

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
     * Add a content type header if it's a json request
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

        if ($this->annotations->exists(Multipart::NAME)) {
            $headers['Content-Type'] = 'multipart/form-data';

            return $headers;
        }

        $headers['Content-Type'] = 'application/x-www-form-urlencoded';

        return $headers;
    }
}

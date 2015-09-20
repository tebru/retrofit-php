<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Http;

/**
 * Class Response
 *
 * Common interface to get the response body
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Response
{
    /**
     * The response body
     *
     * @var string
     */
    private $response;

    /**
     * Constructor
     *
     * @param string $responseBody
     */
    public function __construct($responseBody)
    {
        $this->response = $responseBody;
    }

    /**
     * Get the response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->response;
    }

    /**
     * Set the response body
     *
     * @param string $responseBody
     */
    public function setBody($responseBody)
    {
        $this->response = $responseBody;
    }
}

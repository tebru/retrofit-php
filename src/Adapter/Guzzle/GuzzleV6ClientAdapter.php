<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Guzzle;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Tebru;
use Tebru\Retrofit\Adapter\Http\Response;
use Tebru\Retrofit\Adapter\HttpClientAdapter;

/**
 * Class GuzzleV6ClientAdapter
 *
 * Wrapper around version 6 of guzzlehttp/guzzle
 *
 * @author Nate Brunette <n@tebru.net>
 */
class GuzzleV6ClientAdapter implements HttpClientAdapter
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Constructor
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $version = (int)ClientInterface::VERSION;
        Tebru\assertSame(6, $version, 'Guzzle client must be at version 6, version %d found', $version);

        $this->client = $client;
    }

    /**
     * Make a request
     *
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param string $body
     * @return Response
     */
    public function send($method, $uri, array $headers = [], $body = null)
    {
        $response = $this->client->send(new Request($method, $uri, $headers, $body));

        return new Response((string)$response->getBody());
    }
}

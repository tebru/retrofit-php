<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter\Http;

use Tebru\Retrofit\Adapter\HttpClientAdapter;

/**
 * Class RetrofitClientAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitClientAdapter implements HttpClientAdapter
{
    /**
     * Create a request using curl
     *
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param string $body
     * @return Response
     */
    public function send($method, $uri, array $headers = [], $body = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'Retrofit PHP',
        ]);

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        if (null !== $body) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($curl);

        curl_close($curl);

        return new Response($response);
    }
}

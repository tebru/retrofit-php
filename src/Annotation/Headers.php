<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use LogicException;
use OutOfRangeException;
use Tebru;

/**
 * Adds headers literally supplied in the value.
 * 
 *     @Headers("Cache-Control: max-age=640000")
 *     @Headers({
 *         "X-Foo: Bar",
 *         "X-Ping: Pong"
 *     })
 *
 * @author Nate Brunette <n@tebru.net>
 * @Annotation
 */
class Headers
{
    /**
     * @var array $headers
     */
    private $headers = [];

    /**
     * Constructor
     *
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        Tebru\assert(isset($params['value']), new OutOfRangeException('Argument not found for @Headers annotation'));

        // convert to array
        if (!is_array($params['value'])) {
            $params['value'] = [$params['value']];
        }

        // loop through each string and break on ':'
        foreach ($params['value'] as $header) {
            $pos = strpos($header, ':');

            Tebru\assert(false !== $pos, new LogicException('Header in an incorrect format.  Expected "Name: value"'));

            $name = trim(substr($header, 0, $pos));
            $value = trim(substr($header, $pos + 1));

            $this->headers[$name] = $value;
        }
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}

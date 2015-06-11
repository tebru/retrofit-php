<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use OutOfRangeException;
use Tebru;
use Tebru\Retrofit\Exception\AnnotationConditionMissingException;

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
     * @throws OutOfRangeException
     */
    public function __construct(array $params)
    {
        Tebru\assert(isset($params['value']), new AnnotationConditionMissingException(sprintf('An argument was not passed to a "%s" annotation.', get_class($this))));

        // convert to array
        if (!is_array($params['value'])) {
            $params['value'] = [$params['value']];
        }

        // loop through each string and break on ':'
        foreach ($params['value'] as $header) {
            $pos = strpos($header, ':');

            Tebru\assert(false !== $pos, new AnnotationConditionMissingException('Header in an incorrect format.  Expected "Name: value"'));

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

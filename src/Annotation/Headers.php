<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use OutOfRangeException;
use Tebru;
use Tebru\Dynamo\Annotation\DynamoAnnotation;

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
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Headers implements DynamoAnnotation
{
    const NAME = 'headers';

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
        Tebru\assertArrayKeyExists('value', $params, 'An argument was not passed to a "%s" annotation.', get_class($this));

        // convert to array
        $params['value'] = (array) $params['value'];

        // loop through each string and break on ':'
        foreach ($params['value'] as $header) {
            $pos = strpos($header, ':');

            Tebru\assertThat(false !== $pos, 'Header in an incorrect format.  Expected "Name: value"');

            $name = trim(substr($header, 0, $pos));
            $value = trim(substr($header, $pos + 1));

            $this->headers[$name] = $value;
        }
    }

    /**
     * Get the headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * The name of the annotation or class of annotations
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Whether or not multiple annotations of this type can
     * be added to a method
     *
     * @return bool
     */
    public function allowMultiple()
    {
        return false;
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Query parameter keys and values appended to the URL.
 *
 * Both keys and values are converted to strings Values are URL encoded.
 *
 * Simple Example:
 * 
 *     @GET("/search")
 *     @QueryMap("queryParams")
 *
 * Calling the defined method with `["foo" => "bar", "kit" => "kat"]`
 * yields `/search?foo=bar&kit=kat`.
 * Passing `['key' => ['foo' => 'bar']]` will result in `/search?key[foo]=bar`.
 *
 * If the variable name differs from the desired part name, you may specify a
 * different variable name using the `var=` parameter on this annotation.
 * 
 *     @GET("/search")
 *     @QueryMap("queryParams", var="myQueryMapVar")
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class QueryMap extends VariableMapper implements DynamoAnnotation
{
    const NAME = 'query_map';

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

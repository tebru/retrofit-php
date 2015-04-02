<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

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
 * Calling the defined pethod with `["foo" => "bar", "kit" => "kat"]` 
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
 * @Annotation
 */
class QueryMap extends AnnotationToVariableMap
{
}

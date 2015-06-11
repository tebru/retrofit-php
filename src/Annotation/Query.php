<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Query parameter appended to the URL.
 *
 * Values are converted to strings and then URL encoded.
 * 
 * Simple Example:
 * 
 *     @GET("/list")
 *     @Query("page")
 *
 * If the variable name differs from the desired part name, you may specify a
 * different variable name using the `var=` parameter on this annotation. 
 *
 *     @Query("page", var="inputPage")
 *
 * @author Nate Brunette <n@tebru.net>
 * @Annotation
 * @Target("METHOD")
 */
class Query extends AnnotationToVariableMap
{
}

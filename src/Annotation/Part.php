<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Denotes a single part of a multi-part request.
 * 
 * This annotation defines a method parameter that will be added as a part of 
 * the request. If the variable name differs from the desired part name, you 
 * may specify a different variable name using the `var=` parameter on this
 * annotation. 
 * You can define multiple of these annotations for multiple variable parts.
 *
 *     @Part("part1")
 *     @Part("part2", var="foo")
 *
 * @author Nate Brunette <n@tebru.net>
 * @Annotation
 */
class Part extends AnnotationToVariableMap
{
}

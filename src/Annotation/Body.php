<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Define the body of the HTTP request.
 * 
 * Use this annotation on a service when you want to directly control the 
 * request body of a request (instead of sending in as request parameters or 
 * form-style request body).
 *
 * @author Nate Brunette <n@tebru.net>
 * @Annotation
 */
class Body extends AnnotationToVariableMap
{
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

/**
 * Represents an HTTP header name/value pair to be attached to the request.
 *
 * @author Nate Brunette <n@tebru.net>
 * @Annotation
 * @Target("METHOD")
 */
class Header extends AnnotationToVariableMap
{
}

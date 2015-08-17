<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Class BaseUrl
 *
 * Use this annotation to override the base url
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target("METHOD")
 */
class BaseUrl extends VariableMapper implements DynamoAnnotation
{
    const NAME = 'base_url';

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

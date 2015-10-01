<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Define the body of the HTTP request.
 * 
 * Use this annotation on a service when you want to directly control the 
 * request body of a request (instead of sending in as request parameters or 
 * form-style request body).
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Body extends VariableMapper implements DynamoAnnotation
{
    const NAME = 'body';

    /**
     * If the body implements \JsonSerializable
     *
     * @var boolean
     */
    private $jsonSerializable;

    public function __construct(array $params)
    {
        parent::__construct($params);

        $this->jsonSerializable = (isset($params['jsonSerializable'])) ? $params['jsonSerializable'] : false;
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
     * @return boolean
     */
    public function isJsonSerializable()
    {
        return $this->jsonSerializable;
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

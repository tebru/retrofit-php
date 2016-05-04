<?php
/*
 * Copyright (c) Nate Brunette.
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
    private $jsonSerializable = false;

    /**
     * Constructor
     *
     * @param array $params
     * @throws \Exception
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        if (!array_key_exists('jsonSerializable', $params)) {
            return;
        }

        $this->jsonSerializable = $params['jsonSerializable'];

        trigger_error(
            'Retrofit Deprecation: The jsonSerializable option is getting removed in the next major version
            of retrofit.  Implementing \JsonSerializable will be sufficient.',
            E_USER_DEPRECATED
        );
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
     * @deprecated This method is deprecated.  Retrofit now looks checks for the interface directly.
     * @return boolean
     */
    public function isJsonSerializable()
    {
        trigger_error(
            'Retrofit Deprecation: The jsonSerializable option is getting removed in the next major version
            of retrofit.  Implementing \JsonSerializable will be sufficient.',
            E_USER_DEPRECATED
        );

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

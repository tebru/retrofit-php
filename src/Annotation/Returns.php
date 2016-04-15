<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use JMS\Serializer\Exception\LogicException;
use Tebru;
use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Defines what type of object a request returns, so that it may be deserialized.
 * 
 * The default is array. Other acceptable values are raw or any type specified 
 * in the Serializer documentation. A raw return will return the API response as
 * a string.
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Returns implements DynamoAnnotation
{
    const NAME = 'returns';

    /**
     * @var string $return
     */
    private $return;

    /**
     * Constructor
     *
     * @param array $params
     * @throws LogicException
     */
    public function __construct(array $params)
    {
        Tebru\assertArrayKeyExists('value', $params, 'An argument was not passed to a "%s" annotation.', get_class($this));

        $this->return = $params['value'];
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
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

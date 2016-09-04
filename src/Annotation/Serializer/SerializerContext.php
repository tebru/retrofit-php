<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation\Serializer;

use Tebru;
use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Class SerializerContext
 *
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class SerializerContext implements DynamoAnnotation
{
    const NAME = 'serializer_context';

    /**
     * @var array
     */
    private $values;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        Tebru\assertArrayKeyExists('value', $params, 'SerializerContext must be passed a value');
        Tebru\assertTrue(is_array($params['value']), 'SerializerContext value must be an array');

        $this->values = $params['value'];
    }

    /**
     * @return array
     */
    public function getContext()
    {
        return $this->values;
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

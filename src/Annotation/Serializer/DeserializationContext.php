<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Annotation\Serializer;

use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * DeserializationContext
 *
 * Define a context when deserializing an object from a response.
 *
 * @author Matthew Loberg <m@mloberg.com>
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class DeserializationContext extends JmsSerializerContext implements DynamoAnnotation
{
    const NAME = 'deserialization_context';

    /**
     * @var int
     */
    private $depth;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (array_key_exists('depth', $params)) {
            $this->depth = $params['depth'];
            unset($params['depth']);
        }

        parent::__construct($params);
    }

    /**
     * Get Depth
     *
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
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

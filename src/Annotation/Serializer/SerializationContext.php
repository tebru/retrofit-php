<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Annotation\Serializer;

use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * SerializationContext
 *
 * Define a context when serializing an object for a request.
 *
 * @author Matthew Loberg <m@mloberg.com>
 * @author Nate Brunette <n@tebru.net>
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class SerializationContext extends JmsSerializerContext implements DynamoAnnotation
{
    const NAME = 'serialization_context';

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

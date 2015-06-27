<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Handler\Serializer;

use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Handler\AnnotationHandler;
use Tebru\Retrofit\Model\Method;

/**
 * DeserializationContextHandler
 *
 * @author Matthew Loberg <m@mloberg.com>
 */
class DeserializationContextHandler implements AnnotationHandler
{
    /**
     * Will set annotation data to $method
     *
     * @param Method                 $method
     * @param DeserializationContext $annotation
     *
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setDeserializationContext([
            'depth' => $annotation->getDepth(),
            'groups' => $annotation->getGroups(),
            'serializeNull' => $annotation->getSerializeNull(),
            'version' => $annotation->getVersion(),
            'attributes' => $annotation->getAttributes(),
        ]);
    }
}

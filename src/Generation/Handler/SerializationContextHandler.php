<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\Serializer\DeserializationContext;
use Tebru\Retrofit\Annotation\Serializer\SerializationContext;

/**
 * Class SerializationContextHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class SerializationContextHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        $this->setSerializationContext();
        $this->setDeserializationContext();
    }

    /**
     * Set serialization context if annotation exists
     *
     * @return null
     */
    public function setSerializationContext()
    {
        if (!$this->annotations->exists(SerializationContext::NAME)) {
            return null;
        }

        /** @var SerializationContext $contextAnnotation */
        $contextAnnotation = $this->annotations->get(SerializationContext::NAME);
        $context = [
            'groups' => $contextAnnotation->getGroups(),
            'version' => $contextAnnotation->getVersion(),
            'serializeNull' => $contextAnnotation->getSerializeNull(),
            'enableMaxDepthChecks' => $contextAnnotation->getEnableMaxDepthChecks(),
            'attributes' => $contextAnnotation->getAttributes(),
        ];

        $this->methodBodyBuilder->setSerializationContext($context);
    }

    /**
     * Set deserialization context if annotaiton exists
     *
     * @return null
     */
    public function setDeserializationContext()
    {
        if (!$this->annotations->exists(DeserializationContext::NAME)) {
            return null;
        }

        /** @var DeserializationContext $contextAnnotation */
        $contextAnnotation = $this->annotations->get(DeserializationContext::NAME);
        $context = [
            'groups' => $contextAnnotation->getGroups(),
            'version' => $contextAnnotation->getVersion(),
            'serializeNull' => $contextAnnotation->getSerializeNull(),
            'enableMaxDepthChecks' => $contextAnnotation->getEnableMaxDepthChecks(),
            'attributes' => $contextAnnotation->getAttributes(),
            'depth' => $contextAnnotation->getDepth(),
        ];

        $this->methodBodyBuilder->setDeserializationContext($context);
    }
}

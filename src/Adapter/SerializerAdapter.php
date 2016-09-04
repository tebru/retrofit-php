<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter;

/**
 * Interface SerializerAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface SerializerAdapter
{
    /**
     * Serialize an object to a string
     *
     * @param mixed $data The data that should be serialized
     * @param array $context Serializer specific context
     * @return string
     */
    public function serialize($data, array $context = []);

    /**
     * Serialize an object to an array
     *
     * @param mixed $data The data that should be serialized
     * @param array $context Serializer specific context
     * @return array
     */
    public function toArray($data, array $context = []);

    /**
     * Set the format that data should be serialized to
     *
     * @param string $serializeTo
     */
    public function setSerializeTo($serializeTo);

    /**
     * Set the default serialization context
     *
     * @param array $context
     */
    public function setDefaultSerializationContext(array $context);
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Adapter;

/**
 * Interface DeserializerAdapter
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface DeserializerAdapter
{
    /**
     * Deserialize a formatted string to a specific type
     *
     * @param string $data Data represented as a string
     * @param string $deserializeTo What data should be deserialized to
     * @param array $context Deserializer specific context
     * @return mixed
     */
    public function deserialize($data, $deserializeTo, array $context = []);

    /**
     * Deserialize an array to a specific type
     *
     * @param array $data Data represented as an array
     * @param string $deserializeTo What data should be deserialized to
     * @param array $context Deserializer specific context
     * @return mixed
     */
    public function fromArray(array $data, $deserializeTo, array $context = []);

    /**
     * The current format data is serialized as
     *
     * @param string $deserializeFrom
     */
    public function setDeserializeFrom($deserializeFrom);

    /**
     * Set the default deserialization context
     *
     * @param array $context
     */
    public function setDefaultDeserializationContext(array $context);
}

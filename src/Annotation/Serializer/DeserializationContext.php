<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Annotation\Serializer;

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
class DeserializationContext extends DeserializerContext
{
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        parent::__construct(['value' => $params]);

        trigger_error(
            'Retrofit Deprecation: @DeserializationContext is deprecated and will be removed in v3.  Use @DeserializerContext instead.',
            E_USER_DEPRECATED
        );
    }
}

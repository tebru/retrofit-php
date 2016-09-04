<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Annotation\Serializer;

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
class SerializationContext extends SerializerContext
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $params)
    {
        parent::__construct(['value' => $params]);

        trigger_error(
            'Retrofit Deprecation: @SerializationContext is deprecated and will be removed in v3.  Use @SerializerContext instead.',
            E_USER_DEPRECATED
        );
    }
}

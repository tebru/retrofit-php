<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Annotation;

/**
 * SerializationContext
 *
 * Define a context when serializing an object for a request.
 *
 * @author Matthew Loberg <m@mloberg.com>
 * @Annotation
 * @Target("METHOD")
 */
class SerializationContext
{
    /**
     * @var array|string
     */
    private $groups;

    /**
     * @var bool
     */
    private $serializeNull = false;

    /**
     * @var int
     */
    private $version;

    /**
     * @var array
     */
    private $attributes;

    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        if (isset($params['groups'])) {
            $this->groups = $params['groups'];
            unset($params['groups']);
        }

        if (isset($params['serializeNull'])) {
            $this->serializeNull = $params['serializeNull'];
            unset($params['serializeNull']);
        }

        if (isset($params['version'])) {
            $this->version = $params['version'];
            unset($params['version']);
        }

        $this->attributes = $params;
    }

    /**
     * Get Groups
     *
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Get SerializeNull
     *
     * @return mixed
     */
    public function getSerializeNull()
    {
        return $this->serializeNull;
    }

    /**
     * Get Version
     *
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get Attributes
     *
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */
 
namespace Tebru\Retrofit\Annotation\Serializer;

/**
 * DeserializationContext
 *
 * Define a context when deserializing an object from a response.
 *
 * @author Matthew Loberg <m@mloberg.com>
 * @Annotation
 * @Target("METHOD")
 */
class DeserializationContext
{
    /**
     * @var int
     */
    private $depth;

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

        if (isset($params['depth'])) {
            $this->depth = $params['depth'];
            unset($params['depth']);
        }

        $this->attributes = $params;
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

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation\Serializer;

/**
 * Class JmsSerializerContext
 *
 * @author Matthew Loberg <m@mloberg.com>
 * @author Nate Brunette <n@tebru.net>
 */
abstract class JmsSerializerContext
{
    /**
     * @var array|string
     */
    private $groups;

    /**
     * @var int
     */
    private $version;

    /**
     * @var bool
     */
    private $serializeNull = false;

    /**
     * @var bool
     */
    private $enableMaxDepthChecks = false;

    /**
     * @var array
     */
    private $attributes = [];

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

        if (isset($params['version'])) {
            $this->version = $params['version'];
            unset($params['version']);
        }

        if (isset($params['serializeNull'])) {
            $this->serializeNull = $params['serializeNull'];
            unset($params['serializeNull']);
        }

        if (isset($params['enableMaxDepthChecks'])) {
            $this->enableMaxDepthChecks = $params['enableMaxDepthChecks'];
            unset($params['enableMaxDepthChecks']);
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
     * Get version
     *
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
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
     * Get enable max depth checks
     *
     * @return mixed
     */
    public function getEnableMaxDepthChecks()
    {
        return $this->enableMaxDepthChecks;
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

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Api;

/**
 * Class MockAvatar
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MockAvatar
{
    /**
     * @var resource|string
     */
    private $avatar;

    /**
     * Constructor
     *
     * @param string|resource $avatar
     */
    public function __construct($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return string|resource
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string|resource $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

}

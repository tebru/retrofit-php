<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Api;

use JsonSerializable;

/**
 * Class MockApiUserSerializable
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MockApiUserSerializable extends MockApiUser implements JsonSerializable
{
    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return ['name' => $this->getName(), 'age' => $this->getAge(), 'enabled' => $this->isEnabled()];
    }
}

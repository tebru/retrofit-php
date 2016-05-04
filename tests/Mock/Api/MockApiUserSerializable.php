<?php
/*
 * Copyright (c) Nate Brunette.
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
    public function jsonSerialize()
    {
        return ['name' => $this->getName(), 'age' => $this->getAge(), 'enabled' => $this->isEnabled()];
    }
}

<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Api;

use JsonSerializable;

/**
 * Class MockAvatarSerializable
 *
 * @author Nate Brunette <n@tebru.net>
 */
class MockAvatarSerializable extends MockAvatar implements JsonSerializable
{
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return ['avatar' => $this->getAvatar()];
    }
}

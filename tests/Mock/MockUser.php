<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock;

use JMS\Serializer\Annotation as JMS;
use JsonSerializable;

/**
 * Class MockUser
 *
 * @author Nate Brunette <nbrunett@nerdery.com>
 */
class MockUser implements JsonSerializable
{
    /**
     * @JMS\Type("integer")
     */
    public $id;

    /**
     * @JMS\Type("string")
     */
    public $name;

    /**
     * @JMS\Type("string")
     * @JMS\Groups({"Default", "test"})
     */
    public $email;

    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}

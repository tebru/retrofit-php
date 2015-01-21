<?php

namespace Tebru\Retrofit\Test\Functional\Mock;

use JMS\Serializer\Annotation as JMS;
use JsonSerializable;

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

    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}

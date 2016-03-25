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
     * @JMS\Groups({"Default"})
     */
    public $name;

    /**
     * @JMS\Type("string")
     * @JMS\Groups({"Default", "test"})
     */
    public $email;

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}

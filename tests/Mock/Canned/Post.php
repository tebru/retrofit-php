<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Mock\Canned;

use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;

/**
 * Class Post
 *
 * @author Nate Brunette <n@tebru.net>
 */
class Post
{
    /**
     * @Type("integer")
     * @Groups({"list"})
     */
    public $id;

    /**
     * @Type("string")
     * @Groups({"list", "detail"})
     */
    public $title;
}

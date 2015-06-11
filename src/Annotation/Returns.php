<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use OutOfRangeException;
use Tebru;

/**
 * Defines what type of object a request returns, so that it may be deserialized.
 * 
 * The default is array. Other acceptable values are raw or any type specified 
 * in the Serializer documentation. A raw return will return the API response as
 * a string.
 *
 * @author Nate Brunette <n@tebru.net>
 * @Annotation
 */
class Returns
{
    /**
     * @var string $return
     */
    private $return;

    /**
     * Constructor
     *
     * @param array $params
     * @throws OutOfRangeException
     */
    public function __construct(array $params)
    {
        Tebru\assert(isset($params['value']), new OutOfRangeException('@Returns expects an argument, none found.'));

        $this->return = $params['value'];
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }
}

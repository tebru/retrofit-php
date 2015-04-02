<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Exception;

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
     * @throws Exception
     */
    public function __construct(array $params)
    {
        if (!isset($params['value'])) {
            throw new Exception('Return parameter name not set');
        }

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

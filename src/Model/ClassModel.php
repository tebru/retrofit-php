<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Model;

/**
 * Class ClassModel
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ClassModel
{
    /**
     * Array of class level headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * Collection of methods
     *
     * @var Method[]
     */
    private $methods = [];

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Add a method to the collection
     *
     * Will set the class headers to the method headers first
     *
     * @param Method $method
     */
    public function addMethod(Method $method)
    {
        $method->addHeaders($this->getHeaders());
        $this->methods[] = $method->toArray();
    }
}

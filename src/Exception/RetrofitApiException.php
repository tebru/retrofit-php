<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Exception;

use Exception;

/**
 * Class RetrofitApiException
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RetrofitApiException extends Exception
{
    /**
     * The client class that the exception came from
     *
     * @var string
     */
    private $clientClass;

    /**
     * @param string $clientClass
     * @param string $message
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($clientClass, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->clientClass = $clientClass;
    }

    /**
     * Get the generated client class name
     *
     * @return string
     */
    public function getClientClass()
    {
        return $this->clientClass;
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Symfony\Component\EventDispatcher\Event;
use Tebru\Retrofit\Adapter\Http\Response;

/**
 * Class AfterSendEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class AfterSendEvent extends Event
{
    /**
     * @var Response
     */
    private $response;

    /**
     * Constructor
     *
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}

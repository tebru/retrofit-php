<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ReturnEvent
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnEvent extends Event
{
    const NAME = 'retrofit.return';

    /**
     * @var mixed
     */
    private $return;

    /**
     * Constructor
     *
     * @param mixed $return
     */
    public function __construct($return)
    {
        $this->return = $return;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }
}

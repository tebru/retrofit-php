<?php
/*
 * Copyright (c) Nate Brunette.
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
     * What will be returned from the generated client
     *
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
     * Get return
     *
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * Set updated return back to event
     *
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }
}

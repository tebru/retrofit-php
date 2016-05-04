<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Event;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Interface EventDispatcherAware
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface EventDispatcherAware
{
    /**
     * Set a symfony event dispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     * @return null
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher);
}

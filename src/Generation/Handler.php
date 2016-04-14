<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation;

/**
 * Interface Handler
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface Handler
{
    /**
     * Each handler is a callable
     *
     * @param HandlerContext $context
     * @return null
     */
    public function __invoke(HandlerContext $context);
}

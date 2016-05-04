<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation;

use Tebru\Retrofit\Generation\Handler;

/**
 * Class HandlerStack
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HandlerStack
{
    /**
     * @var Handler[]
     */
    private $stack = [];

    /**
     * @var HandlerContext
     */
    private $context;

    /**
     * Constructor
     *
     * @param HandlerContext $context
     */
    public function __construct(HandlerContext $context)
    {
        $this->context = $context;
    }

    /**
     * Add a handler to the stack
     *
     * @param Handler $handler
     */
    public function push(Handler $handler)
    {
        $this->stack[] = $handler;
    }

    /**
     * Loop through all handlers, providing the context
     */
    public function execute()
    {
        foreach ($this->stack as $handler) {
            $handler($this->context);
        }
    }
}

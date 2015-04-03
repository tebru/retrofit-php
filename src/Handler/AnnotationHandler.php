<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Model\Method;

/**
 * Interface AnnotationHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
interface AnnotationHandler
{
    /**
     * Will set annotation data to $method
     *
     * @param Method $method
     * @param mixed $annotation
     * @return null
     */
    public function handle(Method $method, $annotation);
}

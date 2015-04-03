<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Model\Method;

/**
 * Class BodyHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Body $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setOptions(['body' => $annotation->getValue()]);
    }
}

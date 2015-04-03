<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Header;
use Tebru\Retrofit\Model\Method;

/**
 * Class HeaderHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class HeaderHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Header $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->addHeaders([$annotation->getKey() => $annotation->getValue()]);
    }
}

<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Url;
use Tebru\Retrofit\Model\Method;

/**
 * Class UrlHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class UrlHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Url $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->setUrl($annotation->getValue());
    }
}

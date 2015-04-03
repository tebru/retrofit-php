<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\QueryMap;
use Tebru\Retrofit\Model\Method;

/**
 * Class QueryMapHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class QueryMapHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param QueryMap $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->addQueryMap($annotation->getValue());
    }
}

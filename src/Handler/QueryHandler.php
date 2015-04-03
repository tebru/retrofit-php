<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Handler;

use Tebru\Retrofit\Annotation\Query;
use Tebru\Retrofit\Model\Method;

/**
 * Class QueryHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class QueryHandler implements AnnotationHandler
{
    /**
     * @param Method $method
     * @param Query $annotation
     * @return null
     */
    public function handle(Method $method, $annotation)
    {
        $method->addQueries([$annotation->getKey() => $annotation->getValue()]);
    }
}

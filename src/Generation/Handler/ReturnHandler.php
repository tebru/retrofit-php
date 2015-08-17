<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\Returns;

/**
 * Class ReturnHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        if (!$this->annotations->exists(Returns::NAME)) {
            return null;
        }

        /** @var Returns $returnAnnotation */
        $returnAnnotation = $this->annotations->get(Returns::NAME);
        $this->methodBodyBuilder->setReturnType($returnAnnotation->getReturn());
    }
}

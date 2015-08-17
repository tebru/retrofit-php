<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\BaseUrl;

/**
 * Class BaseUrlHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BaseUrlHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        if (!$this->annotations->exists(BaseUrl::NAME)) {
            return null;
        }

        /** @var BaseUrl $baseUrlAnnotation */
        $baseUrlAnnotation = $this->annotations->get(BaseUrl::NAME);

        $this->methodBodyBuilder->setBaseUrl($baseUrlAnnotation->getVariable());
    }
}

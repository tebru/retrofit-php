<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Annotation\Part;

/**
 * Class RequestBodyHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class RequestBodyHandler extends Handler
{
    /** {@inheritdoc} */
    public function handle()
    {
        $this->setBody();
        $this->setBodyParts();
    }

    /**
     * Set body if annotation exists
     *
     * @return null
     */
    private function setBody()
    {
        if (!$this->annotations->exists(Body::NAME)) {
            return null;
        }

        /** @var Body $bodyAnnotation */
        $bodyAnnotation = $this->annotations->get(Body::NAME);

        $parameter = $this->methodModel->getParameter($bodyAnnotation->getVariableName());

        $this->methodBodyBuilder->setBodyIsObject($parameter->isObject());
        $this->methodBodyBuilder->setBody($bodyAnnotation->getVariable());
    }

    /**
     * Set body parts if annotation exists
     *
     * @return null
     */
    private function setBodyParts()
    {
        if (!$this->annotations->exists(Part::NAME)) {
            return null;
        }

        $parts = [];
        /** @var Part $partAnnotation */
        foreach ($this->annotations->get(Part::NAME) as $partAnnotation) {
            $parts[$partAnnotation->getRequestKey()] = $partAnnotation->getVariable();
        }

        $this->methodBodyBuilder->setBodyParts($parts);
    }
}

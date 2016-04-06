<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use Tebru\Retrofit\Annotation\ResponseType;
use Tebru\Retrofit\Annotation\Returns;
use Tebru\Retrofit\Exception\RetrofitException;

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
        $return = $returnAnnotation->getReturn();
        $this->methodBodyBuilder->setReturnType($return);

        if ('Response' === $return) {
            if (!$this->annotations->exists(ResponseType::NAME)) {
                throw new RetrofitException('When using a Response return type, an @ResponseType must also be set.');
            }

            /** @var ResponseType $responseAnnotation */
            $responseAnnotation = $this->annotations->get(ResponseType::NAME);
            $responseType = $responseAnnotation->getType();

            $this->methodBodyBuilder->setResponseType($responseType);
        }
    }
}

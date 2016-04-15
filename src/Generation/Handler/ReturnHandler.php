<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use LogicException;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;

/**
 * Class ReturnHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ReturnHandler implements Handler
{
    /**
     * Create request object and handle send/response
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     * @throws RetrofitException
     */
    public function __invoke(HandlerContext $context)
    {
        $callback = $context->annotations()->getCallback();

        if ($callback !== null) {
            if ($context->annotations()->isCallbackOptional()) {
                $context->body()->add('if (%s !== null) {', $callback);
                $context->body()->add('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);');
                $context->body()->add('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
                $context->body()->add('return $returnEvent->getReturn();');
                $context->body()->add('}');
            } else {
                $context->body()->add('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent(null);');
                $context->body()->add('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
                $context->body()->add('return $returnEvent->getReturn();');

                return;
            }
        }

        $returnType = 'array';
        if (null !== $context->annotations()->getReturnType()) {
            $returnType = $context->annotations()->getReturnType();
        }

        $responseReturn = (null !== $context->annotations()->getResponseType());
        if ($responseReturn) {
            $returnType = $context->annotations()->getResponseType();
        }

        $deserializationContext = (null !== $context->annotations()->getDeserializationContext())
            ? $context->annotations()->getDeserializationContext()
            : [];

        if ('Response' === $returnType && false === $responseReturn) {
            throw new RetrofitException('A method return a Response must include a @ResponseType annotation.');
        }

        $context->body()->add(
            '$retrofitResponse = new \Tebru\Retrofit\Http\Response($response, "%s", $this->serializer, %s);',
            $returnType,
            $context->printer()->printArray($deserializationContext)
        );

        if ($responseReturn) {
            $context->body()->add('$return = $retrofitResponse;');
        } else {
            $context->body()->add('$return = $retrofitResponse->body();');
        }

        $context->body()->add('$returnEvent = new \Tebru\Retrofit\Event\ReturnEvent($return);');
        $context->body()->add('$this->eventDispatcher->dispatch("retrofit.return", $returnEvent);');
        $context->body()->add('return $returnEvent->getReturn();');    }
}

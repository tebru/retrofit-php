<?php
/*
 * Copyright (c) Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Listener;

use Tebru;
use Tebru\Dynamo\Event\MethodEvent;
use Tebru\Dynamo\Model\Body;
use Tebru\Retrofit\Generation\Handler\BodyHandler;
use Tebru\Retrofit\Generation\HandlerContext;
use Tebru\Retrofit\Generation\HandlerStack;
use Tebru\Retrofit\Generation\Handler\HeaderHandler;
use Tebru\Retrofit\Generation\Handler\ResponseHandler;
use Tebru\Retrofit\Generation\Handler\ReturnHandler;
use Tebru\Retrofit\Generation\Handler\UrlHandler;
use Tebru\Retrofit\Generation\Printer\ArrayPrinter;
use Tebru\Retrofit\Generation\Provider\AnnotationProvider;

/**
 * Class DynamoMethodListener
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoMethodListener
{
    /**
     * Handle the event
     *
     * @param MethodEvent $event
     */
    public function __invoke(MethodEvent $event)
    {
        $methodModel = $event->getMethodModel();
        $annotations = $event->getAnnotationCollection();

        $annotationProvider = new AnnotationProvider($annotations, $methodModel);
        $body = new Body();

        $stack = new HandlerStack(new HandlerContext($annotationProvider, $body, new ArrayPrinter()));
        $stack->push(new UrlHandler());
        $stack->push(new HeaderHandler());
        $stack->push(new BodyHandler());
        $stack->push(new ResponseHandler());
        $stack->push(new ReturnHandler());

        $stack->execute();

        $methodModel->setBody($body);
    }
}

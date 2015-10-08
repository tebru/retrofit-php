<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Listener;

use Tebru;
use Tebru\Dynamo\Event\MethodEvent;
use Tebru\Retrofit\Generation\Builder\Factory\MethodBodyBuilderFactory;
use Tebru\Retrofit\Generation\Handler\Factory\HandlerFactory;
use Tebru\Retrofit\Generation\Handler\Handler;

/**
 * Class DynamoMethodListener
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoMethodListener
{
    /**
     * Creates annotation handlers
     *
     * @var HandlerFactory
     */
    private $handlerFactory;

    /**
     * Creates a new method body builder
     *
     * @var MethodBodyBuilderFactory
     */
    private $methodBodyBuilderFactory;

    /**
     * Constructor
     *
     * @param HandlerFactory $handlerFactory
     * @param MethodBodyBuilderFactory $methodBodyBuilderFactory
     */
    public function __construct(HandlerFactory $handlerFactory, MethodBodyBuilderFactory $methodBodyBuilderFactory)
    {
        $this->handlerFactory = $handlerFactory;
        $this->methodBodyBuilderFactory = $methodBodyBuilderFactory;
    }

    /**
     * Handler the event
     *
     * @param MethodEvent $event
     */
    public function __invoke(MethodEvent $event)
    {
        $methodModel = $event->getMethodModel();
        $annotations = $event->getAnnotationCollection();
        $methodBodyBuilder = $this->methodBodyBuilderFactory->make();

        $handlers = [
            $this->handlerFactory->baseUrl($methodModel, $methodBodyBuilder, $annotations),
            $this->handlerFactory->serializationContext($methodModel, $methodBodyBuilder, $annotations),
            $this->handlerFactory->requestUrl($methodModel, $methodBodyBuilder, $annotations),
            $this->handlerFactory->requestHeader($methodModel, $methodBodyBuilder, $annotations),
            $this->handlerFactory->requestBody($methodModel, $methodBodyBuilder, $annotations),
            $this->handlerFactory->returns($methodModel, $methodBodyBuilder, $annotations),
            $this->handlerFactory->asyncCallback($methodModel, $methodBodyBuilder, $annotations),
        ];

        /** @var Handler $handler */
        foreach ($handlers as $handler) {
            $handler->handle();
        }

        $body = $methodBodyBuilder->build();
        $methodModel->setBody($body);
    }
}

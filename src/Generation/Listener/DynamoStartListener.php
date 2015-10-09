<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Listener;

use Tebru\Dynamo\Event\StartEvent;
use Tebru\Dynamo\Model\ClassModel;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\ParameterModel;
use Tebru\Dynamo\Model\PropertyModel;

/**
 * Class DynamoStartListener
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoStartListener
{
    /**
     * Handle the event
     *
     * @param StartEvent $event
     */
    public function __invoke(StartEvent $event)
    {
        $classModel = $event->getClassModel();
        $this->addProperties($classModel);
        $this->addConstructor($classModel);
        $this->addWait($classModel);
    }

    /**
     * Add properties to the class
     *
     * @param ClassModel $classModel
     */
    private function addProperties(ClassModel $classModel)
    {
        $baseUrl = new PropertyModel($classModel, 'baseUrl');
        $client = new PropertyModel($classModel, 'client');
        $serializer = new PropertyModel($classModel, 'serializer');
        $eventDispatcher = new PropertyModel($classModel, 'eventDispatcher');

        $classModel->addProperty($baseUrl);
        $classModel->addProperty($client);
        $classModel->addProperty($serializer);
        $classModel->addProperty($eventDispatcher);
    }

    /**
     * Create constructor
     *
     * @param ClassModel $classModel
     */
    private function addConstructor(ClassModel $classModel)
    {
        $methodModel = new MethodModel($classModel, '__construct');

        $baseUrl = new ParameterModel($methodModel, 'baseUrl', false);

        $client = new ParameterModel($methodModel, 'client', false);
        $client->setTypeHint('\Tebru\Retrofit\Adapter\HttpClientAdapter');

        $serializer = new ParameterModel($methodModel, 'serializer', false);
        $serializer->setTypeHint('\JMS\Serializer\SerializerInterface');

        $eventDispatcher = new ParameterModel($methodModel, 'eventDispatcher', false);
        $eventDispatcher->setTypeHint('\Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $methodModel->addParameter($baseUrl);
        $methodModel->addParameter($client);
        $methodModel->addParameter($serializer);
        $methodModel->addParameter($eventDispatcher);

        $methodBody = [
            '$this->baseUrl = $baseUrl;',
            '$this->client = $client;',
            '$this->serializer = $serializer;',
            '$this->eventDispatcher = $eventDispatcher;',
        ];

        $methodModel->setBody(implode($methodBody));

        $classModel->addMethod($methodModel);
    }

    /**
     * Create wait method
     *
     * @param ClassModel $classModel
     * @return void
     */
    private function addWait(ClassModel $classModel)
    {
        $reflectionClass = new \ReflectionClass($classModel->getInterface());

        if (!in_array('Tebru\Retrofit\Http\AsyncAware', $reflectionClass->getInterfaceNames())) {
            return;
        }

        $methodModel = new MethodModel($classModel, 'wait');

        $methodModel->setBody('$this->client->wait();');

        $classModel->addMethod($methodModel);
    }
}

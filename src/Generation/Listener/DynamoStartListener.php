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

        $classModel->addProperty($baseUrl);
        $classModel->addProperty($client);
        $classModel->addProperty($serializer);
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
        $client->setTypeHint('\Guzzle\Http\ClientInterface');

        $serializer = new ParameterModel($methodModel, 'serializer', false);
        $serializer->setTypeHint('\JMS\Serializer\SerializerInterface');

        $methodModel->addParameter($baseUrl);
        $methodModel->addParameter($client);
        $methodModel->addParameter($serializer);

        $methodBody = [
            '$this->baseUrl = $baseUrl;',
            '$this->client = $client;',
            '$this->serializer = $serializer;',
        ];

        $methodModel->setBody(implode($methodBody));

        $classModel->addMethod($methodModel);
    }
}

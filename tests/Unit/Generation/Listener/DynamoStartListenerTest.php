<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Generation\Listener;

use Mockery;
use Tebru\Dynamo\Event\StartEvent;
use Tebru\Dynamo\Model\ClassModel;
use Tebru\Dynamo\Model\MethodModel;
use Tebru\Dynamo\Model\PropertyModel;
use Tebru\Retrofit\Generation\Listener\DynamoStartListener;
use Tebru\Retrofit\Test\Mock\Service\MockServiceAsync;
use Tebru\Retrofit\Test\Mock\Service\MockServiceBaseUrl;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class DynamoStartListenerTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class DynamoStartListenerTest extends MockeryTestCase
{
    public function testHandleEvent()
    {
        $event = Mockery::mock(StartEvent::class);
        $classModel = Mockery::mock(ClassModel::class);

        $event->shouldReceive('getClassModel')->times(1)->withNoArgs()->andReturn($classModel);
        $classModel->shouldReceive('addProperty')->times(4)->with(Mockery::type(PropertyModel::class))->andReturnNull();
        $classModel->shouldReceive('addMethod')->times(1)->with(Mockery::type(MethodModel::class));
        $classModel->shouldReceive('getInterface')->times(1)->withNoArgs()->andReturn(MockServiceBaseUrl::class);

        $listener = new DynamoStartListener();

        $this->assertNull($listener($event));
    }

    public function testHandleEventWithAsync()
    {
        $event = Mockery::mock(StartEvent::class);
        $classModel = Mockery::mock(ClassModel::class);

        $event->shouldReceive('getClassModel')->times(1)->withNoArgs()->andReturn($classModel);
        $classModel->shouldReceive('addProperty')->times(4)->with(Mockery::type(PropertyModel::class))->andReturnNull();
        $classModel->shouldReceive('addMethod')->times(2)->with(Mockery::type(MethodModel::class));
        $classModel->shouldReceive('getInterface')->times(1)->withNoArgs()->andReturn(MockServiceAsync::class);

        $listener = new DynamoStartListener();

        $this->assertNull($listener($event));
    }
}

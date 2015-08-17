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
        $classModel->shouldReceive('addProperty')->times(3)->with(Mockery::type(PropertyModel::class))->andReturnNull();
        $classModel->shouldReceive('addMethod')->times(1)->with(Mockery::type(MethodModel::class));

        $listener = new DynamoStartListener();

        $this->assertNull($listener($event));
    }
}

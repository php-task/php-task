<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Event;

use Task\Event\TaskFailedEvent;
use Task\TaskInterface;

/**
 * Tests for class TaskFailedEvent.
 */
class TaskFailedEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTask()
    {
        $task = $this->prophesize(TaskInterface::class);
        $exception = $this->prophesize(\Exception::class);

        $event = new TaskFailedEvent($task->reveal(), $exception->reveal());

        $this->assertEquals($task->reveal(), $event->getTask());
    }

    public function testGetException()
    {
        $task = $this->prophesize(TaskInterface::class);
        $exception = $this->prophesize(\Exception::class);

        $event = new TaskFailedEvent($task->reveal(), $exception->reveal());

        $this->assertEquals($exception->reveal(), $event->getException());
    }
}

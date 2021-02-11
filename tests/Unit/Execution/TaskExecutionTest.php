<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Execution;

use PHPUnit\Framework\TestCase;
use Task\Execution\TaskExecution;
use Task\Task;
use Task\TaskStatus;

/**
 * Tests for TaskExecution.
 */
class TaskExecutionTest extends TestCase
{
    public function testUuid()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime(), null, '123-123-123');

        $this->assertEquals('123-123-123', $execution->getUuid());
    }

    public function testDuration()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertNull($execution->getDuration());

        $execution->setDuration(1.222);
        $this->assertEquals(1.222, $execution->getDuration());
    }

    public function testEndTime()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertNull($execution->getEndTime());

        $date = new \DateTime('now');
        $execution->setEndTime($date);
        $this->assertEquals($date, $execution->getEndTime());
    }

    public function testException()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertNull($execution->getException());

        $exception = new \Exception();
        $execution->setException($exception);
        $this->assertEquals($exception, $execution->getException());
    }

    public function testResult()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertNull($execution->getResult());

        $result = 'test result';
        $execution->setResult($result);
        $this->assertEquals($result, $execution->getResult());
    }

    public function testStartTime()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertNull($execution->getStartTime());

        $date = new \DateTime('now');
        $execution->setStartTime($date);
        $this->assertEquals($date, $execution->getStartTime());
    }

    public function testStatus()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertNull($execution->getStatus());

        $execution->setStatus(TaskStatus::COMPLETED);
        $this->assertEquals(TaskStatus::COMPLETED, $execution->getStatus());
    }

    public function testWorkload()
    {
        $task = $this->prophesize(Task::class);
        $workload = 'test workload';
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime(), $workload);

        $this->assertEquals($workload, $execution->getWorkload());
    }

    public function testHandlerClass()
    {
        $task = $this->prophesize(Task::class);
        $execution = new TaskExecution($task->reveal(), \stdClass::class, new \DateTime());

        $this->assertEquals(\stdClass::class, $execution->getHandlerClass());
    }

    public function testScheduleTime()
    {
        $task = $this->prophesize(Task::class);
        $date = new \DateTime('now');
        $execution = new TaskExecution($task->reveal(), \stdClass::class, $date);

        $this->assertEquals($date, $execution->getScheduleTime());
    }
}

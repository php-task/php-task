<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Builder;

use Cron\CronExpression;
use PHPUnit\Framework\TestCase;
use Task\Builder\TaskBuilder;
use Task\Scheduler\TaskSchedulerInterface;
use Task\TaskInterface;

/**
 * Tests for TaskBuilder.
 */
class TaskBuilderTest extends TestCase
{
    public function testHourly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->hourly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@hourly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testDaily()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->daily($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@daily'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testWeekly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->weekly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@weekly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testMonthly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->monthly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@monthly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testYearly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->yearly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@yearly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testCron()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->cron('0 * * * *', $firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('0 * * * *'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testExecuteAt()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $executionDate = new \DateTime('+1 day');
        $this->assertEquals($taskBuilder, $taskBuilder->executeAt($executionDate));

        $task->setFirstExecution($executionDate)->shouldBeCalled();
    }

    public function testSchedule()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskScheduler = $this->prophesize(TaskSchedulerInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal(), $taskScheduler->reveal());

        $this->assertEquals($task->reveal(), $taskBuilder->schedule());

        $taskScheduler->addTask($task->reveal())->shouldBeCalled();
    }
}

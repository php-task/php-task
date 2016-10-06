<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Unit\Builder;

use Cron\CronExpression;
use Task\Builder\TaskBuilder;
use Task\TaskInterface;

/**
 * Tests for TaskBuilder.
 */
class TaskBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testHourly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->hourly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@hourly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testDaily()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->daily($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@daily'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testWeekly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->weekly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@weekly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testMonthly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->monthly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@monthly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testYearly()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->yearly($firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('@yearly'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testCron()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $firstExecution = new \DateTime('-1 day');
        $lastExecution = new \DateTime('+1 day');

        $this->assertEquals($taskBuilder, $taskBuilder->cron('0 * * * *', $firstExecution, $lastExecution));

        $task->setInterval(CronExpression::factory('0 * * * *'), $firstExecution, $lastExecution)->shouldBeCalled();
    }

    public function testGetTask()
    {
        $task = $this->prophesize(TaskInterface::class);
        $taskBuilder = new TaskBuilder($task->reveal());

        $this->assertEquals($task->reveal(), $taskBuilder->getTask());
    }
}

<?php

namespace Unit;

use Prophecy\Argument;
use Task\FrequentTask\DailyTask;
use Task\SchedulerInterface;
use Task\Task;
use Task\TaskBuilder;
use Task\TaskInterface;

class TaskBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $this->assertInstanceOf(TaskBuilder::class, $taskBuilder);

        return [$taskBuilder, $scheduler];
    }

    public function testSchedule()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $scheduler->schedule(
            Argument::that(
                function (Task $task) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $taskBuilder->schedule();
    }

    public function testDaily()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $start = new \DateTime('1 day ago');
        $end = new \DateTime('+2 day');

        $scheduler->schedule(
            Argument::that(
                function (DailyTask $task) use ($start, $end) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());

                    $this->assertEquals($start, $task->getStart());
                    $this->assertEquals($end, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $this->assertEquals($taskBuilder, $taskBuilder->daily($start, $end));
        $taskBuilder->schedule();
    }

    public function testDailyWithoutEnd()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $start = new \DateTime('1 day ago');

        $scheduler->schedule(
            Argument::that(
                function (DailyTask $task) use ($start) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());

                    $this->assertEquals($start, $task->getStart());
                    $this->assertEquals(null, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $this->assertEquals($taskBuilder, $taskBuilder->daily($start));
        $taskBuilder->schedule();
    }

    public function testDailyWithoutStartAndEnd()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $scheduler->schedule(
            Argument::that(
                function (DailyTask $task) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());

                    $this->assertEquals(new \DateTime(), $task->getStart(), '', 2);
                    $this->assertEquals(null, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $this->assertEquals($taskBuilder, $taskBuilder->daily());
        $taskBuilder->schedule();
    }

    public function testDailyWithoutStart()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $end = new \DateTime('+2 day');

        $scheduler->schedule(
            Argument::that(
                function (DailyTask $task) use ($end) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());

                    $this->assertEquals(new \DateTime(), $task->getStart(), '', 2);
                    $this->assertEquals($end, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $this->assertEquals($taskBuilder, $taskBuilder->daily(null, $end));
        $taskBuilder->schedule();
    }

    public function testSetExecutionDate()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $date = new \DateTime('1 day ago');

        $scheduler->schedule(
            Argument::that(
                function (TaskInterface $task) use ($date) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());
                    $this->assertEquals($date, $task->getExecutionDate());

                    $this->assertInstanceOf(Task::class, $task);

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $this->assertEquals($taskBuilder, $taskBuilder->setExecutionDate($date));
        $taskBuilder->schedule();
    }

    public function testSetKey()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $key = 'test-key';

        $scheduler->schedule(
            Argument::that(
                function (TaskInterface $task) use ($key) {
                    $this->assertEquals('test-name', $task->getTaskName());
                    $this->assertEquals('test-workload', $task->getWorkload());
                    $this->assertEquals($key, $task->getKey());

                    $this->assertInstanceOf(Task::class, $task);

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        $this->assertEquals($taskBuilder, $taskBuilder->setKey($key));
        $taskBuilder->schedule();
    }
}

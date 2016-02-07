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

        self::assertInstanceOf(TaskBuilder::class, $taskBuilder);

        return [$taskBuilder, $scheduler];
    }

    public function testSchedule()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $scheduler->schedule(
            Argument::that(
                function (Task $task) {
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());

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
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());

                    self::assertEquals($start, $task->getStart());
                    self::assertEquals($end, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->daily($start, $end));
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
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());

                    self::assertEquals($start, $task->getStart());
                    self::assertEquals(null, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->daily($start));
        $taskBuilder->schedule();
    }

    public function testDailyWithoutStartAndEnd()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $scheduler->schedule(
            Argument::that(
                function (DailyTask $task) {
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());

                    self::assertEquals(new \DateTime(), $task->getStart(), '', 2);
                    self::assertEquals(null, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->daily());
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
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());

                    self::assertEquals(new \DateTime(), $task->getStart(), '', 2);
                    self::assertEquals($end, $task->getEnd());

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->daily(null, $end));
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
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());
                    self::assertEquals($date, $task->getExecutionDate());

                    self::assertInstanceOf(Task::class, $task);

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->setExecutionDate($date));
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
                    self::assertEquals('test-name', $task->getTaskName());
                    self::assertEquals('test-workload', $task->getWorkload());
                    self::assertEquals($key, $task->getKey());

                    self::assertInstanceOf(Task::class, $task);

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->setKey($key));
        $taskBuilder->schedule();
    }

    public function testImmediately()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $taskBuilder = TaskBuilder::create($scheduler->reveal(), 'test-name', 'test-workload');

        $scheduler->schedule(
            Argument::that(
                function (TaskInterface $task) {
                    self::assertEquals(new \DateTime(), $task->getExecutionDate(), '', 2);

                    self::assertInstanceOf(Task::class, $task);

                    return true;
                }
            )
        )->shouldBeCalledTimes(1);

        self::assertEquals($taskBuilder, $taskBuilder->immediately());
        $taskBuilder->schedule();
    }
}

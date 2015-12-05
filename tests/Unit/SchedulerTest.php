<?php

namespace Unit;

use Prophecy\Argument;
use Task\Handler\RegistryInterface;
use Task\Scheduler;
use Task\Storage\StorageInterface;
use Task\TaskBuilder;
use Task\TaskInterface;

class SchedulerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTask()
    {
        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);

        $registry->run(Argument::any(), Argument::any())->shouldNotBeCalled();
        $registry->has(Argument::any())->shouldNotBeCalled();

        $storage->store(Argument::any())->shouldNotBeCalled();
        $storage->findScheduled()->shouldNotBeCalled();

        $scheduler = new Scheduler($storage->reveal(), $registry->reveal());

        $result = $scheduler->createTask('test', 'test-workload');

        $this->assertInstanceOf(TaskBuilder::class, $result);
    }

    public function testSchedule()
    {
        $task = $this->prophesize(TaskInterface::class);

        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);

        $registry->run(Argument::any(), Argument::any())->shouldNotBeCalled();
        $registry->has(Argument::any())->shouldNotBeCalled();

        $storage->store($task->reveal())->shouldBeCalledTimes(1);
        $storage->findScheduled()->shouldNotBeCalled();

        $scheduler = new Scheduler($storage->reveal(), $registry->reveal());

        $scheduler->schedule($task->reveal());
    }
}

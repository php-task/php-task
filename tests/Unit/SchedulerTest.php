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

    public function scheduleProvider()
    {
        return [
            ['task-1', true],
            ['task-1', false],
            ['task-2', true],
            ['task-2', false],
        ];
    }

    /**
     * @dataProvider scheduleProvider
     */
    public function testSchedule($taskName, $exists = true)
    {
        if (!$exists) {
            $this->setExpectedExceptionRegExp(\Exception::class, sprintf('#.*"%s".*#', $taskName));
        }

        $task = $this->prophesize(TaskInterface::class);
        $task->getTaskName()->willReturn($taskName);

        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);

        $registry->run(Argument::any(), Argument::any())->shouldNotBeCalled();
        $registry->has($taskName)->willReturn($exists);
        $registry->has(Argument::any())->willReturn(false);

        if ($exists) {
            $storage->store($task->reveal())->shouldBeCalledTimes(1);
        } else {
            $storage->store(Argument::any())->shouldNotBeCalled();
        }
        $storage->findScheduled()->shouldNotBeCalled();

        $scheduler = new Scheduler($storage->reveal(), $registry->reveal());

        $scheduler->schedule($task->reveal());
    }

    public function runProvider()
    {
        return [
            [],
            [[['test-1', 'workload-1', 'result-1']]],
            [[['test-1', 'workload-1', 'result-1'], ['test-2', 'workload-2', 'result-2']]],
        ];
    }

    /**
     * @dataProvider runProvider
     */
    public function testRun($taskData = [])
    {
        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);

        $tasks = array_map(
            function ($item) use ($registry) {
                $task = $this->prophesize(TaskInterface::class);
                $task->getTaskName()->willReturn($item[0]);
                $task->getExecutionDate()->willReturn(new \DateTime('1 minute ago'));
                $task->getWorkload()->willReturn($item[1]);
                $task->isCompleted()->willReturn(false);

                $registry->run($item[0], $item[1])->shouldBeCalledTimes(1)->willReturn($item[2]);
                $registry->has($item[0])->shouldBeCalledTimes(1)->willReturn(true);

                $task->setResult($item[2])->shouldBeCalledTimes(1);
                $task->setCompleted()->shouldBeCalledTimes(1);

                return $task->reveal();
            },
            $taskData
        );

        $registry->has(Argument::any())->willReturn(false);

        $storage->store(Argument::any())->shouldNotBeCalled();
        $storage->findScheduled()->willReturn($tasks);

        $scheduler = new Scheduler($storage->reveal(), $registry->reveal());

        $scheduler->run();
    }

    // TODO run with frequent tasks.
}

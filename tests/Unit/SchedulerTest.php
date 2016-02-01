<?php

namespace Unit;

use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Task\FrequentTask\FrequentTaskInterface;
use Task\Handler\RegistryInterface;
use Task\Scheduler;
use Task\Storage\StorageInterface;
use Task\TaskBuilderFactoryInterface;
use Task\TaskBuilderInterface;
use Task\TaskInterface;

class SchedulerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateTask()
    {
        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);
        $factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcher::class);

        $registry->run(Argument::any(), Argument::any())->shouldNotBeCalled();
        $registry->has(Argument::any())->shouldNotBeCalled();

        $storage->store(Argument::any())->shouldNotBeCalled();
        $storage->findScheduled()->shouldNotBeCalled();

        $scheduler = new Scheduler(
            $storage->reveal(),
            $registry->reveal(),
            $factory->reveal(),
            $eventDispatcher->reveal()
        );

        $scheduler->createTask('test', 'test-workload');

        $factory->create($scheduler, 'test', 'test-workload')->shouldBeCalledTimes(1);
    }

    public function createTaskAndScheduleProvider()
    {
        return [
            ['test-handler'],
            ['test-handler', 'test-workload'],
            ['test-handler', 'test-workload', 'daily'],
            ['test-handler', 'test-workload', 'daily', 'test-key'],
            ['test-handler', null, 'daily', 'test-key'],
            ['test-handler', null, null, 'test-key'],
            ['test-handler', 'test-workload', null, 'test-key'],
        ];
    }

    /**
     * @dataProvider createTaskAndScheduleProvider
     */
    public function testCreateTaskAndSchedule($handlerName, $workload = null, $interval = null, $key = null)
    {
        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);
        $factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $taskBuilder = $this->prophesize(TaskBuilderInterface::class);
        $task = $this->prophesize(TaskInterface::class);

        $scheduler = new Scheduler(
            $storage->reveal(),
            $registry->reveal(),
            $factory->reveal(),
            $eventDispatcher->reveal()
        );

        $factory->create($scheduler, $handlerName, $workload)->willReturn($taskBuilder->reveal());

        if ($interval) {
            $taskBuilder->{$interval}(null, null)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
        } else {
            // TODO add other intervals
            $taskBuilder->daily(null, null)->shouldNotBeCalled();
        }

        if ($key) {
            $taskBuilder->setKey($key)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
        } else {
            $taskBuilder->setKey(Argument::any())->shouldNotBeCalled();
        }

        $taskBuilder->schedule()->willReturn($task->reveal());

        $result = $scheduler->createTaskAndSchedule($handlerName, $workload, $interval, $key);

        self::assertEquals($task->reveal(), $result);
    }

    public function testCreateTaskAndScheduleWithCron()
    {
        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);
        $factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $taskBuilder = $this->prophesize(TaskBuilderInterface::class);
        $task = $this->prophesize(TaskInterface::class);

        $scheduler = new Scheduler(
            $storage->reveal(),
            $registry->reveal(),
            $factory->reveal(),
            $eventDispatcher->reveal()
        );

        $factory->create($scheduler, 'test', null)->willReturn($taskBuilder->reveal());
        $taskBuilder->cron('0 0 * * * *')->shouldBeCalled();
        $taskBuilder->schedule()->willReturn($task->reveal());

        $result = $scheduler->createTaskAndSchedule('test', null, '0 0 * * * *');

        self::assertEquals($task->reveal(), $result);
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
        $factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcher::class);

        $registry->run(Argument::any(), Argument::any())->shouldNotBeCalled();
        $registry->has($taskName)->willReturn($exists);
        $registry->has(Argument::any())->willReturn(false);

        if ($exists) {
            $storage->store($task->reveal())->shouldBeCalledTimes(1);
        } else {
            $storage->store(Argument::any())->shouldNotBeCalled();
        }
        $storage->findScheduled()->shouldNotBeCalled();

        $scheduler = new Scheduler(
            $storage->reveal(),
            $registry->reveal(),
            $factory->reveal(),
            $eventDispatcher->reveal()
        );

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
        $factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcher::class);

        $tasks = $this->mapTasks($taskData, $registry);

        $registry->has(Argument::any())->willReturn(false);

        $storage->store(Argument::any())->shouldNotBeCalled();
        $storage->findScheduled()->willReturn($tasks);
        $storage->persist(Argument::any())->willReturn(true);

        $scheduler = new Scheduler(
            $storage->reveal(),
            $registry->reveal(),
            $factory->reveal(),
            $eventDispatcher->reveal()
        );

        $scheduler->run();
    }

    /**
     * @dataProvider runProvider
     */
    public function testRunFrequent($taskData = [])
    {
        $storage = $this->prophesize(StorageInterface::class);
        $registry = $this->prophesize(RegistryInterface::class);
        $factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $eventDispatcher = $this->prophesize(EventDispatcher::class);

        $scheduler = new Scheduler(
            $storage->reveal(),
            $registry->reveal(),
            $factory->reveal(),
            $eventDispatcher->reveal()
        );

        $tasks = $this->mapTasks($taskData, $registry, true);

        $registry->has(Argument::any())->willReturn(false);

        $storage->store(Argument::any())->shouldNotBeCalled();
        $storage->findScheduled()->willReturn($tasks);
        $storage->persist(Argument::any())->willReturn(true);

        $scheduler->run();
    }

    private function mapTasks($taskData, $registry, $interval = false)
    {
        return array_map(
            function ($item) use ($registry, $interval) {
                $task = $this->prophesize($interval ? FrequentTaskInterface::class : TaskInterface::class);
                $task->getTaskName()->willReturn($item[0]);
                $task->getExecutionDate()->willReturn(new \DateTime('1 minute ago'));
                $task->getWorkload()->willReturn($item[1]);
                $task->isCompleted()->willReturn(false);

                $registry->run($item[0], $item[1])->shouldBeCalledTimes(1)->willReturn($item[2]);
                $registry->has($item[0])->shouldBeCalledTimes(1)->willReturn(true);

                $task->setResult($item[2])->shouldBeCalledTimes(1);
                $task->setCompleted()->shouldBeCalledTimes(1);

                if ($interval) {
                    $task->scheduleNext(Argument::type(Scheduler::class))->shouldBeCalledTimes(1);
                }

                return $task->reveal();
            },
            $taskData
        );
    }
}

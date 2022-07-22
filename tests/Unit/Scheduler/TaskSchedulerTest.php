<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Scheduler;

use Cron\CronExpression;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
use Task\Builder\TaskBuilderFactoryInterface;
use Task\Builder\TaskBuilderInterface;
use Task\Event\Events;
use Task\Event\TaskEvent;
use Task\Event\TaskExecutionEvent;
use Task\Execution\TaskExecutionInterface;
use Task\Handler\TaskHandlerInterface;
use Task\Scheduler\TaskScheduler;
use Task\Scheduler\TaskSchedulerInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\Storage\TaskRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

/**
 * Tests for TaskScheduler.
 */
class TaskSchedulerTest extends TestCase
{
    /**
     * @var TaskBuilderFactoryInterface
     */
    private $factory;

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $taskExecutionRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TaskSchedulerInterface
     */
    private $taskScheduler;

    protected function setUp(): void
    {
        $this->factory = $this->prophesize(TaskBuilderFactoryInterface::class);
        $this->taskRepository = $this->prophesize(TaskRepositoryInterface::class);
        $this->taskExecutionRepository = $this->prophesize(TaskExecutionRepositoryInterface::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $this->taskScheduler = new TaskScheduler(
            $this->factory->reveal(),
            $this->taskRepository->reveal(),
            $this->taskExecutionRepository->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    public function testCreateTask()
    {
        $task = $this->prophesize(TaskInterface::class);

        $workload = 'Test 1';
        $taskBuilder = $this->prophesize(TaskBuilderInterface::class);
        $this->taskRepository->create(TestHandler::class, $workload)->willReturn($task->reveal());
        $this->factory->createTaskBuilder($task->reveal(), $this->taskScheduler)->willReturn($taskBuilder->reveal());

        $result = $this->taskScheduler->createTask(TestHandler::class, $workload);
        $this->assertEquals($taskBuilder->reveal(), $result);
    }

    public function testAddTask()
    {
        $task = $this->prophesize(TaskInterface::class);
        $task->getInterval()->willReturn(null);
        $task->getFirstExecution()->willReturn(new \DateTime());

        $execution = $this->prophesize(TaskExecutionInterface::class);

        $this->dispatch(
            Events::TASK_CREATE,
            Argument::that(
                function ($event) use ($task) {
                    if ($event instanceof TaskEvent) {
                        return $event->getTask() === $task->reveal();
                    }

                    return false;
                }
            )
        );

        $this->dispatch(
            Events::TASK_EXECUTION_CREATE,
            Argument::that(
                function ($event) use ($task, $execution) {
                    if ($event instanceof TaskExecutionEvent) {
                        return $event->getTask() === $task->reveal() && $event->getTaskExecution() === $execution->reveal();
                    }

                    return false;
                }
            )
        );

        $this->taskRepository->save($task->reveal())->shouldBeCalled();

        $this->taskExecutionRepository->findByTask($task)->willReturn([]);
        $this->taskExecutionRepository->findPending($task)->willReturn(null);
        $this->taskExecutionRepository->create($task, Argument::type(\DateTime::class))->willReturn($execution);
        $this->taskExecutionRepository->save($execution->reveal())->shouldBeCalledTimes(1);

        $this->taskScheduler->addTask($task->reveal());
    }

    public function testScheduleTasks()
    {
        $tasks = [
            $this->createTask($expression1 = CronExpression::factory('@hourly')),
            $this->createTask($expression2 = CronExpression::factory('@yearly')),
            $this->createTask(null, $date = new \DateTime('+1 day')),
        ];

        $this->taskRepository->findEndBeforeNow()->willReturn($tasks);

        // single task
        $this->taskExecutionRepository->findByTask($tasks[2])->willReturn([]);

        // already scheduled
        $this->taskExecutionRepository->findPending($tasks[0])->willReturn(
            $this->prophesize(TaskExecutionInterface::class)->reveal()
        );

        $this->taskExecutionRepository->findPending($tasks[1])->willReturn(null);
        $this->taskExecutionRepository->findPending($tasks[2])->willReturn(null);

        $execution1 = $this->prophesize(TaskExecutionInterface::class);
        $execution1->setStatus(TaskStatus::PLANNED)->shouldBeCalled();
        $this->taskExecutionRepository->create($tasks[1], $expression2->getNextRunDate())->willReturn(
            $execution1->reveal()
        );
        $this->taskExecutionRepository->save($execution1)->shouldBeCalled();

        $execution2 = $this->prophesize(TaskExecutionInterface::class);
        $execution2->setStatus(TaskStatus::PLANNED)->shouldBeCalled();
        $this->taskExecutionRepository->create($tasks[2], $date)->willReturn($execution2->reveal());
        $this->taskExecutionRepository->save($execution2)->shouldBeCalled();

        $this->dispatch(
            Events::TASK_EXECUTION_CREATE,
            Argument::that(
                function (TaskExecutionEvent $event) use ($tasks, $execution1) {
                    return $event->getTask() === $tasks[1] && $event->getTaskExecution() === $execution1->reveal();
                }
            )
        );

        $this->dispatch(
            Events::TASK_EXECUTION_CREATE,
            Argument::that(
                function (TaskExecutionEvent $event) use ($tasks, $execution2) {
                    return $event->getTask() === $tasks[2] && $event->getTaskExecution() === $execution2->reveal();
                }
            )
        );

        $this->taskScheduler->scheduleTasks();
    }

    private function createTask($interval, $firstExecution = null)
    {
        $task = $this->prophesize(TaskInterface::class);
        $task->getInterval()->willReturn($interval);
        $task->getFirstExecution()->willReturn($firstExecution);

        return $task->reveal();
    }

    private function dispatch($eventName, $event)
    {
        return $this->eventDispatcher->dispatch($event, $eventName)->shouldBeCalled()->willReturnArgument(0);
    }
}

class TestHandler implements TaskHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($workload)
    {
        return $workload;
    }
}

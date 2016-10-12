<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Runner;

use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Task\Event\Events;
use Task\Event\TaskExecutionEvent;
use Task\Execution\TaskExecution;
use Task\Handler\TaskHandlerFactoryInterface;
use Task\Handler\TaskHandlerInterface;
use Task\Runner\TaskRunner;
use Task\Runner\TaskRunnerInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

/**
 * Tests for TaskRunner.
 */
class TaskRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $taskExecutionRepository;

    /**
     * @var TaskHandlerFactoryInterface
     */
    private $taskHandlerFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TaskRunnerInterface
     */
    private $taskRunner;

    protected function setUp()
    {
        $this->taskExecutionRepository = $this->prophesize(TaskExecutionRepositoryInterface::class);
        $this->taskHandlerFactory = $this->prophesize(TaskHandlerFactoryInterface::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $this->taskRunner = new TaskRunner(
            $this->taskExecutionRepository->reveal(),
            $this->taskHandlerFactory->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    public function testRunTasks()
    {
        $task = $this->createTask();
        $executions = [
            $this->createTaskExecution($task, new \DateTime(), 'Test 1'),
            $this->createTaskExecution($task, new \DateTime(), 'Test 2'),
        ];

        $this->taskExecutionRepository->findScheduled()->willReturn($executions);
        $this->taskHandlerFactory->create(TestHandler::class)->willReturn(new TestHandler());

        $this->initializeDispatcher($this->eventDispatcher, $executions[0]);
        $this->initializeDispatcher($this->eventDispatcher, $executions[1]);

        $this->taskExecutionRepository->flush()->shouldBeCalledTimes(1);

        $this->taskRunner->runTasks();

        $this->assertLessThanOrEqual(new \DateTime(), $executions[0]->getStartTime());
        $this->assertLessThanOrEqual(new \DateTime(), $executions[1]->getStartTime());
        $this->assertLessThanOrEqual($executions[1]->getStartTime(), $executions[0]->getStartTime());
        $this->assertLessThanOrEqual($executions[0]->getEndTime(), $executions[0]->getStartTime());
        $this->assertLessThanOrEqual($executions[1]->getEndTime(), $executions[1]->getStartTime());
        $this->assertGreaterThan(0, $executions[0]->getDuration());
        $this->assertGreaterThan(0, $executions[1]->getDuration());
        $this->assertEquals(strrev('Test 1'), $executions[0]->getResult());
        $this->assertEquals(strrev('Test 2'), $executions[1]->getResult());
        $this->assertEquals(TaskStatus::COMPLETE, $executions[0]->getStatus());
        $this->assertEquals(TaskStatus::COMPLETE, $executions[1]->getStatus());
    }

    public function testRunTasksFailed()
    {
        $task = $this->createTask();
        $executions = [
            $this->createTaskExecution($task, new \DateTime(), 'Test 1'),
            $this->createTaskExecution($task, new \DateTime(), 'Test 2'),
        ];

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->handle('Test 1')->willThrow(new \Exception());
        $handler->handle('Test 2')->willReturn(strrev('Test 2'));

        $this->taskExecutionRepository->findScheduled()->willReturn($executions);
        $this->taskHandlerFactory->create(TestHandler::class)->willReturn($handler->reveal());

        $this->initializeDispatcher($this->eventDispatcher, $executions[0], Events::TASK_FAILED);
        $this->initializeDispatcher($this->eventDispatcher, $executions[1]);

        $this->taskExecutionRepository->flush()->shouldBeCalled();

        $this->taskRunner->runTasks();

        $this->assertLessThanOrEqual(new \DateTime(), $executions[0]->getStartTime());
        $this->assertLessThanOrEqual(new \DateTime(), $executions[1]->getStartTime());
        $this->assertLessThanOrEqual($executions[1]->getStartTime(), $executions[0]->getStartTime());
        $this->assertLessThanOrEqual($executions[0]->getEndTime(), $executions[0]->getStartTime());
        $this->assertLessThanOrEqual($executions[1]->getEndTime(), $executions[1]->getStartTime());
        $this->assertGreaterThan(0, $executions[0]->getDuration());
        $this->assertGreaterThan(0, $executions[1]->getDuration());
        $this->assertNull($executions[0]->getResult());
        $this->assertNotNull($executions[0]->getException());
        $this->assertEquals(strrev('Test 2'), $executions[1]->getResult());
        $this->assertNull($executions[1]->getException());
        $this->assertEquals(TaskStatus::FAILED, $executions[0]->getStatus());
        $this->assertEquals(TaskStatus::COMPLETE, $executions[1]->getStatus());
    }

    private function initializeDispatcher($eventDispatcher, $execution, $event = Events::TASK_PASSED)
    {
        $eventDispatcher->dispatch(
            Events::TASK_BEFORE,
            Argument::that(
                function (TaskExecutionEvent $event) use ($execution) {
                    return $event->getTaskExecution() === $execution;
                }
            )
        )->will(
            function () use ($eventDispatcher, $execution, $event) {
                $eventDispatcher->dispatch(
                    $event,
                    Argument::that(
                        function (TaskExecutionEvent $event) use ($execution) {
                            return $event->getTaskExecution() === $execution;
                        }
                    )
                )->shouldBeCalled();
                $eventDispatcher->dispatch(
                    Events::TASK_FINISHED,
                    Argument::that(
                        function (TaskExecutionEvent $event) use ($execution) {
                            return $event->getTaskExecution() === $execution;
                        }
                    )
                )->shouldBeCalled();
            }
        );
    }

    private function createTask()
    {
        $task = $this->prophesize(TaskInterface::class);

        return $task->reveal();
    }

    private function createTaskExecution(
        TaskInterface $task,
        $scheduleTime,
        $workload = 'Test-Workload',
        $handlerClass = TestHandler::class
    ) {
        return new TaskExecution($task, $handlerClass, $scheduleTime, $workload);
    }
}

class TestHandler implements TaskHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($workload)
    {
        return strrev($workload);
    }
}

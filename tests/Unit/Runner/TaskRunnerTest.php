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
use Task\Lock\Exception\LockConflictException;
use Task\Lock\LockingTaskHandlerInterface;
use Task\Lock\LockInterface;
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
     * @var LockInterface
     */
    private $lock;

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
        $this->lock = $this->prophesize(LockInterface::class);
        $this->eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $this->taskRunner = new TaskRunner(
            $this->taskExecutionRepository->reveal(),
            $this->taskHandlerFactory->reveal(),
            $this->lock->reveal(),
            $this->eventDispatcher->reveal()
        );
    }

    public function testRunTasks()
    {
        $task = $this->createTask();
        $executions = [
            $this->createTaskExecution($task, new \DateTime(), 'Test 1')->setStatus(TaskStatus::PLANNED),
            $this->createTaskExecution($task, new \DateTime(), 'Test 2')->setStatus(TaskStatus::PLANNED),
        ];

        $this->taskExecutionRepository->save($executions[0])->willReturnArgument(0)->shouldBeCalledTimes(2);
        $this->taskExecutionRepository->save($executions[1])->willReturnArgument(0)->shouldBeCalledTimes(2);

        $taskExecutionRepository = $this->taskExecutionRepository;
        $this->taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))
            ->will(
                function () use ($executions, $taskExecutionRepository) {
                    $taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))
                        ->will(
                            function () use ($executions, $taskExecutionRepository) {
                                $taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))
                                    ->willReturn(null);

                                return $executions[1];
                            }
                        );

                    return $executions[0];
                }
            );

        $this->taskHandlerFactory->create(TestHandler::class)->willReturn(new TestHandler());

        $this->initializeDispatcher($this->eventDispatcher, $executions[0]);
        $this->initializeDispatcher($this->eventDispatcher, $executions[1]);

        $this->lock->isAcquired($executions[0])->willReturn(false);
        $this->lock->acquire($executions[0])->willReturn(true);
        $this->lock->release($executions[0])->willReturn(true);

        $this->lock->isAcquired($executions[1])->willReturn(false);
        $this->lock->acquire($executions[1])->willReturn(true);
        $this->lock->release($executions[1])->willReturn(true);

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
        $this->assertEquals(TaskStatus::COMPLETED, $executions[0]->getStatus());
        $this->assertEquals(TaskStatus::COMPLETED, $executions[1]->getStatus());
    }

    public function testRunTasksFailed()
    {
        $task = $this->createTask();
        $executions = [
            $this->createTaskExecution($task, new \DateTime(), 'Test 1')->setStatus(TaskStatus::PLANNED),
            $this->createTaskExecution($task, new \DateTime(), 'Test 2')->setStatus(TaskStatus::PLANNED),
        ];

        $this->taskExecutionRepository->save($executions[0])->willReturnArgument(0)->shouldBeCalledTimes(2);
        $this->taskExecutionRepository->save($executions[1])->willReturnArgument(0)->shouldBeCalledTimes(2);

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->handle('Test 1')->willThrow(new \Exception());
        $handler->handle('Test 2')->willReturn(strrev('Test 2'));

        $taskExecutionRepository = $this->taskExecutionRepository;
        $this->taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))
            ->will(
                function () use ($executions, $taskExecutionRepository) {
                    $taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))
                        ->will(
                            function () use ($executions, $taskExecutionRepository) {
                                $taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))
                                    ->willReturn(null);

                                return $executions[1];
                            }
                        );

                    return $executions[0];
                }
            );

        $this->taskHandlerFactory->create(TestHandler::class)->willReturn($handler->reveal());

        $this->initializeDispatcher($this->eventDispatcher, $executions[0], Events::TASK_FAILED);
        $this->initializeDispatcher($this->eventDispatcher, $executions[1]);

        $this->lock->isAcquired($executions[0])->willReturn(false);
        $this->lock->acquire($executions[0])->willReturn(true);
        $this->lock->release($executions[0])->willReturn(true);

        $this->lock->isAcquired($executions[1])->willReturn(false);
        $this->lock->acquire($executions[1])->willReturn(true);
        $this->lock->release($executions[1])->willReturn(true);

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
        $this->assertEquals(TaskStatus::COMPLETED, $executions[1]->getStatus());
    }

    public function testRunTasksNotLocked()
    {
        $task = $this->createTask();
        $execution = $this->createTaskExecution($task, new \DateTime(), 'Test')->setStatus(TaskStatus::PLANNED);

        $this->taskExecutionRepository->save($execution)->willReturnArgument(0)->shouldBeCalledTimes(2);

        $handler = $this->prophesize(LockingTaskHandlerInterface::class);
        $handler->handle('Test')->shouldBeCalled();
        $handler->getLockKey('Test')->willReturn('test-key');
        $this->taskHandlerFactory->create(TestHandler::class)->willReturn($handler->reveal());

        $this->initializeDispatcher($this->eventDispatcher, $execution);

        $taskExecutionRepository = $this->taskExecutionRepository;
        $this->taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))->will(
                function () use ($execution, $taskExecutionRepository) {
                    $taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))->willReturn(null);

                    return $execution;
                }
            );

        $lock = $this->lock;
        $this->lock->isAcquired('test-key')->willReturn(false);
        $this->lock->release('test-key')->willThrow(new LockConflictException('test-key'));
        $this->lock->acquire('test-key')->shouldBeCalled()->will(
            function () use ($lock) {
                $lock->isAcquired('test-key')->willReturn(true);
                $lock->release('test-key')->shouldBeCalledTimes(1)->willReturn(true);
                $lock->acquire('test-key')->willThrow(new LockConflictException('test-key'));

                return true;
            }
        );

        $this->taskRunner->runTasks();
    }

    public function testRunTasksLocked()
    {
        $task = $this->createTask();
        $execution = $this->createTaskExecution($task, new \DateTime(), 'Test')->setStatus(TaskStatus::PLANNED);

        $taskExecutionRepository = $this->taskExecutionRepository;
        $this->taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))->will(
                function () use ($execution, $taskExecutionRepository) {
                    $taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class))->willReturn(null);

                    return $execution;
                }
            );

        $this->lock->isAcquired('test-key')->willReturn(true);
        $this->lock->release('test-key')->shouldNotBeCalled()->willThrow(new LockConflictException($execution));
        $this->lock->acquire('test-key')->shouldNotBeCalled()->willThrow(new LockConflictException($execution));

        $handler = $this->prophesize(LockingTaskHandlerInterface::class);
        $handler->handle('Test')->shouldNotBeCalled();
        $handler->getLockKey('Test')->willReturn('test-key');
        $this->taskHandlerFactory->create(TestHandler::class)->willReturn($handler->reveal());

        $this->taskExecutionRepository->save($execution)->willReturnArgument(0)->shouldNotBeCalled();

        $this->taskRunner->runTasks();
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
                    Events::TASK_AFTER,
                    Argument::that(
                        function (TaskExecutionEvent $event) use ($execution) {
                            return $event->getTaskExecution() === $execution;
                        }
                    )
                )->shouldBeCalled();
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
        $execution = new TaskExecution($task, $handlerClass, $scheduleTime, $workload);

        $this->taskExecutionRepository->findByUuid($execution->getUuid())->willReturn($execution);

        return $execution;
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

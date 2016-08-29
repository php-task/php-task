<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Scheduler;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Task\Event\Events;
use Task\Event\TaskEvent;
use Task\Event\TaskExecutionEvent;
use Task\Execution\TaskExecutionRepositoryInterface;
use Task\FactoryInterface;
use Task\Storage\TaskRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

/**
 * Scheduler creates and manages tasks.
 */
class Scheduler implements SchedulerInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $executionRepository;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param FactoryInterface $factory
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskExecutionRepositoryInterface $executionRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        FactoryInterface $factory,
        TaskRepositoryInterface $taskRepository,
        TaskExecutionRepositoryInterface $executionRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = $factory;
        $this->taskRepository = $taskRepository;
        $this->executionRepository = $executionRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function createTask($handlerClass, $workload = null)
    {
        return $this->factory->createTaskBuilder($handlerClass, $workload);
    }

    /**
     * {@inheritdoc}
     */
    public function addTask(TaskInterface $task)
    {
        $this->eventDispatcher->dispatch(Events::TASK_CREATE, new TaskEvent($task));

        $this->taskRepository->store($task);
        $this->scheduleTasks();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleTasks()
    {
        $tasks = $this->taskRepository->findEndBeforeNow();

        foreach ($tasks as $task) {
            $scheduleTime = $task->getInterval() ?
                $task->getInterval()->getNextRunDate() : $task->getFirstExecution();

            if (null === $this->executionRepository->findByStartTime($task, $scheduleTime)) {
                $execution = $this->factory->createTaskExecution($task, $scheduleTime);
                $execution->setStatus(TaskStatus::PLANNED);

                $this->eventDispatcher->dispatch(
                    Events::TASK_EXECUTION_CREATE,
                    new TaskExecutionEvent($task, $execution)
                );

                $this->executionRepository->store($execution);
            }
        }
    }
}

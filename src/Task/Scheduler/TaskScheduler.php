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
use Task\Builder\TaskBuilderFactoryInterface;
use Task\Event\Events;
use Task\Event\TaskEvent;
use Task\Event\TaskExecutionEvent;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\Storage\TaskRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

/**
 * Scheduler creates and manages tasks.
 */
class TaskScheduler implements TaskSchedulerInterface
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
     * @param TaskBuilderFactoryInterface $factory
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskExecutionRepositoryInterface $taskExecutionRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TaskBuilderFactoryInterface $factory,
        TaskRepositoryInterface $taskRepository,
        TaskExecutionRepositoryInterface $taskExecutionRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->factory = $factory;
        $this->taskRepository = $taskRepository;
        $this->taskExecutionRepository = $taskExecutionRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function createTask($handlerClass, $workload = null)
    {
        return $this->factory->createTaskBuilder($this->taskRepository->create($handlerClass, $workload), $this);
    }

    /**
     * {@inheritdoc}
     */
    public function addTask(TaskInterface $task)
    {
        $this->eventDispatcher->dispatch(Events::TASK_CREATE, new TaskEvent($task));

        $this->taskRepository->persist($task);
        $this->taskRepository->flush();

        $this->scheduleTask($task);
        $this->taskExecutionRepository->flush();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleTasks()
    {
        $tasks = $this->taskRepository->findEndBeforeNow();
        foreach ($tasks as $task) {
            $this->scheduleTask($task);
        }

        $this->taskExecutionRepository->flush();
    }

    /**
     * Schedule execution for given task.
     *
     * @param TaskInterface $task
     */
    protected function scheduleTask(TaskInterface $task)
    {
        $scheduleTime = $task->getInterval() ? $task->getInterval()->getNextRunDate() : $task->getFirstExecution();

        if (null === $this->taskExecutionRepository->findByStartTime($task, $scheduleTime)) {
            $execution = $this->taskExecutionRepository->create($task, $scheduleTime);
            $execution->setStatus(TaskStatus::PLANNED);

            $this->eventDispatcher->dispatch(
                Events::TASK_EXECUTION_CREATE,
                new TaskExecutionEvent($task, $execution)
            );

            $this->taskExecutionRepository->persist($execution);
        }
    }
}

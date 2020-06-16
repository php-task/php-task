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
use Symfony\Component\EventDispatcher\LegacyEventDispatcherProxy;
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
        $this->dispatch(Events::TASK_CREATE, new TaskEvent($task));

        $this->taskRepository->save($task);
        $this->scheduleTask($task);

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
    }

    /**
     * Schedule execution for given task.
     *
     * @param TaskInterface $task
     */
    protected function scheduleTask(TaskInterface $task)
    {
        if (null !== ($execution = $this->taskExecutionRepository->findPending($task))) {
            return;
        }

        if (null === $task->getInterval() && 0 < count($this->taskExecutionRepository->findByTask($task))) {
            return;
        }

        $scheduleTime = $task->getInterval() ? $task->getInterval()->getNextRunDate() : $task->getFirstExecution();
        $execution = $this->taskExecutionRepository->create($task, $scheduleTime);
        $execution->setStatus(TaskStatus::PLANNED);

        $this->dispatch(
            Events::TASK_EXECUTION_CREATE,
            new TaskExecutionEvent($task, $execution)
        );

        $this->taskExecutionRepository->save($execution);
    }

    private function dispatch($eventName, $event)
    {
        if (class_exists(LegacyEventDispatcherProxy::class)) {
            return $this->eventDispatcher->dispatch($event, $eventName);
        } else {
            return $this->eventDispatcher->dispatch($eventName, $event);
        }
    }
}

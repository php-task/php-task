<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Storage\ArrayStorage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Execution\TaskExecution;
use Task\Execution\TaskExecutionInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

/**
 * Storage task-execution in an array.
 */
class ArrayTaskExecutionRepository implements TaskExecutionRepositoryInterface
{
    /**
     * @var Collection
     */
    private $taskExecutionCollection;

    /**
     * @param Collection $taskExecutions
     */
    public function __construct(Collection $taskExecutions = null)
    {
        $this->taskExecutionCollection = $taskExecutions ?: new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function create(TaskInterface $task, \DateTime $scheduleTime)
    {
        return new TaskExecution($task, $task->getHandlerClass(), $scheduleTime, $task->getWorkload());
    }

    /**
     * {@inheritdoc}
     */
    public function persist(TaskExecutionInterface $execution)
    {
        $this->taskExecutionCollection->add($execution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function findByStartTime(TaskInterface $task, \DateTime $scheduleTime)
    {
        $filtered = $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($task, $scheduleTime) {
                return $execution->getTask()->getUuid() === $task->getUuid()
                && $execution->getScheduleTime() === $scheduleTime;
            }
        );

        if ($filtered->count() === 0) {
            return;
        }

        return $filtered->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll($limit = null)
    {
        return $this->taskExecutionCollection->slice(0, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function findScheduled()
    {
        $dateTime = new \DateTime();

        return $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($dateTime) {
                return $execution->getStatus() === TaskStatus::PLANNED && $execution->getScheduleTime() < $dateTime;
            }
        );
    }
}

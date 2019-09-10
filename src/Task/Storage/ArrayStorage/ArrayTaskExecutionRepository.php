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
    public function save(TaskExecutionInterface $execution)
    {
        if ($this->taskExecutionCollection->contains($execution)) {
            return $this;
        }

        $this->taskExecutionCollection->add($execution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(TaskExecutionInterface $execution)
    {
        $this->taskExecutionCollection->removeElement($execution);

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
    public function findPending(TaskInterface $task)
    {
        $filtered = $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($task) {
                return $execution->getTask()->getUuid() === $task->getUuid()
                    && in_array($execution->getStatus(), [TaskStatus::PLANNED, TaskStatus::RUNNING]);
            }
        );

        if (0 === $filtered->count()) {
            return;
        }

        return $filtered->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findByUuid($uuid)
    {
        $filtered = $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($uuid) {
                return $execution->getUuid() === $uuid;
            }
        );

        if (0 === $filtered->count()) {
            return;
        }

        return $filtered->first();
    }

    /**
     * {@inheritdoc}
     */
    public function findByTask(TaskInterface $task)
    {
        return $this->findByTaskUuid($task->getUuid());
    }

    /**
     * {@inheritdoc}
     */
    public function findByTaskUuid($taskUuid)
    {
        return array_values(
            $this->taskExecutionCollection->filter(
                function (TaskExecutionInterface $execution) use ($taskUuid) {
                    return $execution->getTask()->getUuid() === $taskUuid;
                }
            )->toArray()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findAll($page = 1, $pageSize = null)
    {
        return array_values($this->taskExecutionCollection->slice(($page - 1) * $pageSize, $pageSize));
    }

    /**
     * {@inheritdoc}
     */
    public function findNextScheduled(\DateTime $dateTime = null, array $skippedExecutions = [])
    {
        $dateTime = $dateTime ?: new \DateTime();

        $result = $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($dateTime, $skippedExecutions) {
                return TaskStatus::PLANNED === $execution->getStatus()
                    && $execution->getScheduleTime() < $dateTime
                    && !in_array($execution->getUuid(), $skippedExecutions);
            }
        )->first();

        return $result ?: null;
    }
}

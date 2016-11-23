<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Storage;

use Task\Execution\TaskExecutionInterface;
use Task\TaskInterface;

/**
 * Interface for task-execution repository.
 */
interface TaskExecutionRepositoryInterface
{
    /**
     * Create task-execution.
     *
     * @param TaskInterface $task
     * @param \DateTime $scheduleTime
     *
     * @return TaskExecutionInterface
     */
    public function create(TaskInterface $task, \DateTime $scheduleTime);

    /**
     * Persist task-execution.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return $this
     */
    public function persist(TaskExecutionInterface $execution);

    /**
     * Remove task-execution.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return $this
     */
    public function remove(TaskExecutionInterface $execution);

    /**
     * Flush storage.
     *
     * @return $this
     */
    public function flush();

    /**
     * Used to check whether a specific task has been scheduled at a specific time.
     *
     * @param TaskInterface $task
     * @param \DateTime $scheduleTime
     *
     * @return TaskExecutionInterface
     */
    public function findByStartTime(TaskInterface $task, \DateTime $scheduleTime);

    /**
     * Find executions of given task.
     *
     * @param TaskInterface $task
     *
     * @return TaskExecutionInterface[]
     */
    public function findByTask(TaskInterface $task);

    /**
     * Returns all task-executions.
     *
     * @param int $page
     * @param int $pageSize
     *
     * @return TaskExecutionInterface[]
     */
    public function findAll($page = 1, $pageSize = null);

    /**
     * Returns scheduled task-executions.
     *
     * Scheduled-time in the past.
     *
     * @return TaskExecutionInterface[]
     */
    public function findScheduled();
}

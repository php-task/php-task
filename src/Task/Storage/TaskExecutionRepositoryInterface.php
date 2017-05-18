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
     * Save task-execution.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return $this
     */
    public function save(TaskExecutionInterface $execution);

    /**
     * Remove task-execution.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return $this
     */
    public function remove(TaskExecutionInterface $execution);

    /**
     * Used to check whether a specific task has been scheduled at a specific time.
     *
     * @param TaskInterface $task
     *
     * @return TaskExecutionInterface
     */
    public function findPending(TaskInterface $task);

    /**
     * Returns task-execution identified by uuid.
     *
     * @param string $uuid
     *
     * @return TaskExecutionInterface
     */
    public function findByUuid($uuid);

    /**
     * Find executions of given task.
     *
     * @param TaskInterface $task
     *
     * @return TaskExecutionInterface[]
     */
    public function findByTask(TaskInterface $task);

    /**
     * Find executions of given task.
     *
     * @param string $taskUuid
     *
     * @return TaskExecutionInterface[]
     */
    public function findByTaskUuid($taskUuid);

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
     * Returns scheduled task-execution.
     *
     * Scheduled-time in the past relative to given date.
     *
     * @param \DateTime|null $dateTime
     * @param array $skippedExecutions
     *
     * @return TaskExecutionInterface
     */
    public function findNextScheduled(\DateTime $dateTime = null, array $skippedExecutions = []);
}

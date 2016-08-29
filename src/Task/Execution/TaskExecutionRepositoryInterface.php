<?php

namespace Task\Execution;

use Task\TaskInterface;

interface TaskExecutionRepositoryInterface
{
    public function add(TaskExecutionInterface $execution);

    public function get($uuid);

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
     * @param int|null $limit
     *
     * @return TaskExecutionInterface[]
     */
    public function findAll($limit = null);

    public function save(TaskExecutionInterface $execution);

    /**
     * @return TaskExecutionInterface[]
     */
    public function findScheduled();
}

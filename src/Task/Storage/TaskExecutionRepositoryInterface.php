<?php

namespace Task\Storage;

use Task\Execution\TaskExecutionInterface;
use Task\TaskInterface;

interface TaskExecutionRepositoryInterface
{
    /**
     * Returns all task-executions which should be executed.
     *
     * @return TaskExecutionInterface[]
     */
    public function findScheduled();

    /**
     * @param TaskInterface $task
     * @param \DateTime $scheduleTime
     *
     * @return TaskExecutionInterface
     */
    public function findByStartTime(TaskInterface $task, \DateTime $scheduleTime);
}

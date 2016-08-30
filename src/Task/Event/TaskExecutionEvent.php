<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Event;

use Task\Execution\TaskExecutionInterface;
use Task\TaskInterface;

/**
 * Task Execution Events are triggered by the Scheduler during scheduling and run process.
 */
class TaskExecutionEvent extends TaskEvent
{
    /**
     * @var TaskExecutionInterface
     */
    private $taskExecution;

    /**
     * @param TaskInterface $task
     * @param TaskExecutionInterface $taskExecution
     */
    public function __construct(TaskInterface $task, TaskExecutionInterface $taskExecution)
    {
        parent::__construct($task);

        $this->taskExecution = $taskExecution;
    }

    /**
     * Returns task-execution.
     *
     * @return TaskExecutionInterface
     */
    public function getTaskExecution()
    {
        return $this->taskExecution;
    }
}

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

use Task\TaskInterface;

/**
 * Task failed Event will be triggered by the Scheduler when the run of given task fails.
 */
class TaskFailedEvent extends TaskEvent
{
    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @param TaskInterface $task
     * @param \Exception $exception
     */
    public function __construct(TaskInterface $task, \Exception $exception)
    {
        parent::__construct($task);

        $this->exception = $exception;
    }

    /**
     * Returns exception which was thrown by the task.
     *
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}

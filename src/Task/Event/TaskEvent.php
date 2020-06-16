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
 * Task Events are triggered by the Scheduler during scheduling and run process.
 */
class TaskEvent extends BaseEvent
{
    /**
     * @var TaskInterface
     */
    private $task;

    /**
     * @param TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * Returns task.
     *
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }
}

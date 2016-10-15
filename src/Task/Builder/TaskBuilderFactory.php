<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Builder;

use Task\Scheduler\TaskSchedulerInterface;
use Task\TaskInterface;

/**
 * Factory for task.
 */
class TaskBuilderFactory implements TaskBuilderFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createTaskBuilder(TaskInterface $task, TaskSchedulerInterface $taskScheduler)
    {
        return new TaskBuilder($task, $taskScheduler);
    }
}

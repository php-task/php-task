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
 * Interface for task builder factory.
 */
interface TaskBuilderFactoryInterface
{
    /**
     * Returns new task-builder.
     *
     * @param TaskInterface $task
     * @param TaskSchedulerInterface $taskScheduler
     *
     * @return TaskBuilderInterface
     */
    public function createTaskBuilder(TaskInterface $task, TaskSchedulerInterface $taskScheduler);
}

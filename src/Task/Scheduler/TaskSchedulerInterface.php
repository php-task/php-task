<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Scheduler;

use Task\Builder\TaskBuilderInterface;
use Task\TaskInterface;

/**
 * Interface for task-scheduler.
 */
interface TaskSchedulerInterface
{
    /**
     * Returns new task-builder.
     *
     * @param $handlerClass
     * @param string|\Serializable|mixed[] $workload
     *
     * @return TaskBuilderInterface
     */
    public function createTask($handlerClass, $workload = null);

    /**
     * Schedule task.
     *
     * @param TaskInterface $task
     *
     * @return $this
     */
    public function addTask(TaskInterface $task);

    /**
     * Schedules task-executions.
     */
    public function scheduleTasks();
}


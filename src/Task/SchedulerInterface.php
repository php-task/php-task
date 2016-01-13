<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

/**
 * Interface for task scheduler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface SchedulerInterface
{
    /**
     * Create a new task by a new instance of task-builder
     * which implements a fluent task-building interface.
     *
     * @param string $handlerName
     * @param string|\Serializable $workload
     *
     * @return TaskBuilderInterface
     */
    public function createTask($handlerName, $workload = null);

    /**
     * Create a new task with given attributes.
     *
     * @param string $handlerName
     * @param string $interval which interval the task should be scheduled (e.g. daily or null for single task).
     * @param string $workload
     * @param string $key unique key to identify already existing task.
     *
     * @return TaskInterface
     */
    public function createTaskAndSchedule($handlerName, $workload = null, $interval = null, $key = null);

    /**
     * Schedule the given task.
     *
     * @param TaskInterface $task
     *
     * @return TaskInterface
     */
    public function schedule(TaskInterface $task);

    /**
     * Runs all scheduled tasks.
     *
     * Retrieves tasks from storage and executes them.
     */
    public function run();
}

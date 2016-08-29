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

use Task\Execution\TaskExecutionInterface;
use Task\Schedule\Schedule;

/**
 * Interface for task builder factory.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface FactoryInterface
{
    /**
     * Returns new task-builder.
     *
     * @param string $handlerClass
     * @param string|\Serializable $workload
     *
     * @return TaskBuilderInterface
     */
    public function createTaskBuilder($handlerClass, $workload);

    /**
     * Returns new task execution for given task.
     *
     * @param TaskInterface $task
     * @param \DateTime $scheduleTime
     *
     * @return TaskExecutionInterface
     */
    public function createTaskExecution(TaskInterface $task, \DateTime $scheduleTime);

    /**
     * Create a new task.
     *
     * @param string $handlerClass
     * @param string|\Serializable|null $workload
     *
     * @return TaskInterface
     */
    public function createTask($handlerClass, $workload);

    /**
     * Create a new schedule.
     *
     * @param TaskInterface[] $tasks
     *
     * @return Schedule
     */
    public function createSchedule($tasks);
}

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
     * @param string $taskName
     * @param string|\Serializable $workload
     *
     * @return TaskBuilder
     */
    public function createTask($taskName, $workload);

    /**
     * @param TaskInterface $task
     */
    public function schedule(TaskInterface $task);

    /**
     * Runs all scheduled tasks.
     *
     * Retrieves tasks from storage and executes them.
     */
    public function run();
}

<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Scheduler;

use Task\TaskInterface;

/**
 * Interface for task scheduler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface SchedulerInterface
{
    public function createTask($handlerClass, $workload = null);

    public function addTask(TaskInterface $task);

    /**
     * Schedules tasks.
     */
    public function scheduleTasks();
}

<?php

namespace Task\TaskRunner;

use Task\Scheduler\TaskInterface;

/**
 * Defines interface for a task-runner which is able to run tasks immediately.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface ImmediatelyTaskRunnerInterface extends TaskRunnerInterface
{
    /**
     * Run tasks immediately and returns result.
     *
     * @param TaskInterface $task
     *
     * @return mixed
     */
    public function runImmediately(TaskInterface $task);
}

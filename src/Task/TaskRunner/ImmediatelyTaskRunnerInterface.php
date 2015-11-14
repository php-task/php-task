<?php

namespace Task\TaskRunner;

use GuzzleHttp\Promise\PromiseInterface;
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
     * @param string $workerName
     * @param TaskInterface $task
     *
     * @return PromiseInterface
     */
    public function runImmediately($workerName, TaskInterface $task);
}

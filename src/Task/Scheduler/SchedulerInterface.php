<?php

namespace Task\Scheduler;

use GuzzleHttp\Promise\PromiseInterface;

/**
 * Handles tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface SchedulerInterface
{
    /**
     * @param string $workerName
     * @param TaskInterface $task
     */
    public function schedule($workerName, TaskInterface $task);

    /**
     * Run task immediately and returns result.
     *
     * @param string $workerName
     * @param TaskInterface $task
     *
     * @return PromiseInterface
     */
    public function run($workerName, TaskInterface $task);
}

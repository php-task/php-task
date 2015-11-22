<?php

namespace Task\Scheduler;

/**
 * Handles tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface SchedulerInterface
{
    /**
     * Schedule task to process later.
     *
     * @param string $workerName
     * @param TaskInterface $task
     */
    public function schedule($workerName, TaskInterface $task);

    /**
     * Run task immediately and returns result.
     *
     * @param string $workerName
     * @param TaskInterface $task
     */
    public function run($workerName, TaskInterface $task);

    /**
     * Run all scheduled tasks.
     */
    public function execute();
}

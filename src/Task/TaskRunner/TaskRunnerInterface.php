<?php

namespace Task\TaskRunner;

/**
 * Defines interface for a task-runner.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskRunnerInterface
{
    /**
     * Adds a worker to process tasks.
     *
     * @param string $workerName
     * @param WorkerInterface $worker
     */
    public function addWorker($workerName, WorkerInterface $worker);

    /**
     * Process given tasks.
     */
    public function run();
}

<?php

namespace Tasks\TaskRunner;

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
     * @param WorkerInterface $worker
     */
    public function addWorker(WorkerInterface $worker);

    /**
     * Process given tasks.
     */
    public function run();
}

<?php

namespace Task\TaskRunner;

use Task\Scheduler\TaskInterface;

/**
 * Defines interface for a worker.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface WorkerInterface
{
    /**
     * Executes tasks and returns result.
     *
     * @param TaskInterface $task
     *
     * @return mixed
     */
    public function run(TaskInterface $task);

    /**
     * Returns namespace of worker name.
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Returns name of worker.
     *
     * @return string
     */
    public function getName();
}

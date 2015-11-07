<?php

namespace Task\Naming;

use Task\Scheduler\TaskInterface;
use Task\TaskRunner\WorkerInterface;

/**
 * Generates names for all php-task library components.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface NamingFactoryInterface
{
    /**
     * Generates name for given worker.
     *
     * @param WorkerInterface $worker
     *
     * @return string
     */
    public function fromWorker(WorkerInterface $worker);

    /**
     * Generates name for given task.
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    public function fromTask(TaskInterface $task);
}

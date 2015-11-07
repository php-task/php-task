<?php

namespace Tasks\Scheduler;

use Serializable;

/**
 * Defines interface for a task.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskInterface
{
    /**
     * Returns name of requested worker.
     *
     * @return string
     */
    public function getWorkerName();

    /**
     * Returns workload for the worker.
     *
     * @return string|Serializable
     */
    public function getWorkload();
}

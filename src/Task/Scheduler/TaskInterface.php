<?php

namespace Task\Scheduler;

use Serializable;

/**
 * Defines interface for a task.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskInterface
{
    /**
     * Returns workload for the worker.
     *
     * @return string|Serializable
     */
    public function getWorkload();
}

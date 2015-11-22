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
     * Returns workload.
     *
     * @return string|Serializable
     */
    public function getWorkload();

    /**
     * Set completed.
     *
     * @return bool
     */
    public function setCompleted();

    /**
     * Returns state.
     *
     * @return bool
     */
    public function isCompleted();

    /**
     * Returns result.
     *
     * @return string|Serializable
     */
    public function getResult();

    /**
     * Set result.
     *
     * @param string|Serializable $result
     */
    public function setResult($result);
}

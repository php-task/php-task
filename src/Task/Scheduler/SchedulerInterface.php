<?php

namespace Tasks\Scheduler;

/**
 * Handles tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface SchedulerInterface
{
    /**
     * @param TaskInterface $task
     */
    public function schedule(TaskInterface $task);

    /**
     * Run task immediately and returns result.
     *
     * @param TaskInterface $task
     *
     * @return mixed
     */
    public function run(TaskInterface $task);
}

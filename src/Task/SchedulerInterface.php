<?php

namespace Task;

/**
 * Scheduler manages tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface SchedulerInterface
{
    /**
     * @param string $taskName
     * @param string|\Serializable $workload
     *
     * @return TaskBuilder
     */
    public function createTask($taskName, $workload);

    /**
     * @param TaskInterface $task
     */
    public function schedule(TaskInterface $task);

    /**
     *
     */
    public function run();
}

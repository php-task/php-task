<?php

namespace Task;

use Task\FrequendTask\DailyTask;

class TaskBuilder
{
    /**
     * @var SchedulerInterface
     */
    private $scheduler;

    /**
     * @var Task
     */
    private $task;

    public static function create(SchedulerInterface $scheduler, $taskName, $workload)
    {
        return new TaskBuilder($scheduler, new Task($taskName, $workload));
    }

    private function __construct(SchedulerInterface $scheduler, TaskInterface $task)
    {
        $this->scheduler = $scheduler;
        $this->task = $task;
    }

    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilder
     */
    public function daily(\DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new DailyTask($this->task, $start ?: new \DateTime(), $end ?: new \DateTime());

        return $this;
    }

    /**
     * @param \DateTime $executionDate
     *
     * @return TaskBuilder
     */
    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->task->setExecutionDate($executionDate);

        return $this;
    }

    /**
     * Schedules task with given scheduler.
     */
    public function schedule()
    {
        $this->scheduler->schedule($this->task);
    }
}

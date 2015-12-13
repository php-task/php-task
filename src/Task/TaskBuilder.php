<?php

namespace Task;

use Task\FrequentTask\DailyTask;

class TaskBuilder implements TaskBuilderInterface
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
     * {@inheritdoc}
     */
    public function daily(\DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new DailyTask($this->task, $start ?: new \DateTime(), $end ?: new \DateTime());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->task->setExecutionDate($executionDate);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function schedule()
    {
        $this->scheduler->schedule($this->task);
    }
}

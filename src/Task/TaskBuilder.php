<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

use Task\FrequentTask\DailyTask;

/**
 * Builder for tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class TaskBuilder implements TaskBuilderInterface
{
    /**
     * @var SchedulerInterface
     */
    private $scheduler;

    /**
     * @var TaskInterface
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
        $this->task = new DailyTask($this->task, $start ?: new \DateTime(), $end);

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
    public function setKey($key)
    {
        $this->task->setKey($key);

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

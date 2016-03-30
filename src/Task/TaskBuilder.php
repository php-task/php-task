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

use Cron\CronExpression;
use Task\FrequentTask\CronTask;
use Task\FrequentTask\DailyTask;
use Task\FrequentTask\HourlyTask;
use Task\FrequentTask\MonthlyTask;
use Task\FrequentTask\WeeklyTask;
use Task\FrequentTask\YearlyTask;

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
        return new self($scheduler, new Task($taskName, $workload));
    }

    private function __construct(SchedulerInterface $scheduler, TaskInterface $task)
    {
        $this->scheduler = $scheduler;
        $this->task = $task;
    }

    /**
     * {@inheritdoc}
     */
    public function hourly(\DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new HourlyTask($this->task, $start ?: new \DateTime(), $end);

        return $this;
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
    public function weekly(\DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new WeeklyTask($this->task, $start ?: new \DateTime(), $end);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function monthly(\DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new MonthlyTask($this->task, $start ?: new \DateTime(), $end);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function yearly(\DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new YearlyTask($this->task, $start ?: new \DateTime(), $end);
        $this->setExecutionDate($this->task->getNextRunDateTime());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function cron($cronExpression, \DateTime $start = null, \DateTime $end = null)
    {
        $this->task = new CronTask(CronExpression::factory($cronExpression), $this->task, $start, $end);

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
    public function immediately()
    {
        $this->task->setExecutionDate(new \DateTime());

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function schedule()
    {
        return $this->scheduler->schedule($this->task);
    }
}

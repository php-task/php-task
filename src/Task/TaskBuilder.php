<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

use Cron\CronExpression;


/**
 * Builder for tasks.
 */
class TaskBuilder implements TaskBuilderInterface
{
    /**
     * @var TaskInterface
     */
    private $task;

    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * {@inheritdoc}
     */
    public function hourly(\DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->task->setInterval(CronExpression::factory('@hourly'), $firstExecution, $lastExecution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function daily(\DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->task->setInterval(CronExpression::factory('@daily'), $firstExecution, $lastExecution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function weekly(\DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->task->setInterval(CronExpression::factory('@weekly'), $firstExecution, $lastExecution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function monthly(\DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->task->setInterval(CronExpression::factory('@monthly'), $firstExecution, $lastExecution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function yearly(\DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->task->setInterval(CronExpression::factory('@yearly'), $firstExecution, $lastExecution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function cron($cronExpression, \DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->task->setInterval(CronExpression::factory($cronExpression), $firstExecution, $lastExecution);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTask()
    {
        return $this->task;
    }
}

<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\FrequentTask;

use Cron\CronExpression;
use Task\SchedulerInterface;
use Task\TaskInterface;

/**
 * Uses cron-expression to schedule frequent tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class CronTask extends FrequentTask
{
    /**
     * @var CronExpression
     */
    private $expression;

    public function __construct(CronExpression $expression,
        TaskInterface $task,
        \DateTime $start,
        \DateTime $end = null
    ) {
        parent::__construct($task, $start, $end);

        $this->expression = $expression;
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleNext(SchedulerInterface $scheduler)
    {
        $executionDate = $this->getNextRunDateTime();

        if (null !== $this->end && $executionDate > $this->end) {
            return;
        }

        $scheduler->createTask($this->getTaskName(), $this->getWorkload())
            ->cron($this->expression->getExpression(), $this->start, $this->end)
            ->setKey($this->getKey())
            ->setExecutionDate($executionDate)
            ->schedule();
    }

    /**
     * {@inheritdoc}
     */
    public function getNextRunDateTime()
    {
        return $this->expression->getNextRunDate();
    }
}

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

use Task\SchedulerInterface;

/**
 * Extends Event with daily operations.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class DailyTask extends FrequentTask
{
    /**
     * {@inheritdoc}
     */
    public function scheduleNext(SchedulerInterface $scheduler)
    {
        $now = new \DateTime();
        $executionDate = $now->modify('+1 day');

        if (null !== $this->end && $executionDate > $this->end) {
            return;
        }

        $scheduler->createTask($this->getTaskName(), $this->getWorkload())
            ->daily($this->start, $this->end)
            ->setExecutionDate($executionDate)
            ->schedule();
    }
}

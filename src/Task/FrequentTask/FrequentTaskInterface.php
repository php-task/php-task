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
use Task\TaskInterface;

/**
 * Extends Event with frequent operations.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
interface FrequentTaskInterface extends TaskInterface
{
    /**
     * Schedules next task if its in the configured timespan.
     *
     * @param SchedulerInterface $scheduler
     */
    public function scheduleNext(SchedulerInterface $scheduler);

    /**
     * Returns start date of frequent task.
     *
     * @return \DateTime
     */
    public function getStart();

    /**
     * Returns end date of frequent task.
     *
     * @return \DateTime
     */
    public function getEnd();
}

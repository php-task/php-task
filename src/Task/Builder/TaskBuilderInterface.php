<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Builder;

use Task\TaskInterface;

/**
 * Interface for task builder.
 */
interface TaskBuilderInterface
{
    /**
     * Use hourly interval.
     *
     * @param \DateTime $firstExecution
     * @param \DateTime $lastExecution
     *
     * @return $this
     */
    public function hourly(\DateTime $firstExecution = null, \DateTime $lastExecution = null);

    /**
     * Use daily interval.
     *
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return $this
     */
    public function daily(\DateTime $start = null, \DateTime $end = null);

    /**
     * Use weekly interval.
     *
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return $this
     */
    public function weekly(\DateTime $start = null, \DateTime $end = null);

    /**
     * Use monthly interval.
     *
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return $this
     */
    public function monthly(\DateTime $start = null, \DateTime $end = null);

    /**
     * Use yearly interval.
     *
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return $this
     */
    public function yearly(\DateTime $start = null, \DateTime $end = null);

    /**
     * Use given cron-interval.
     *
     * @param string $cronExpression
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return $this
     */
    public function cron($cronExpression, \DateTime $start = null, \DateTime $end = null);

    /**
     * Set execution-date.
     *
     * @param \DateTime $executionDate
     *
     * @return $this
     */
    public function executeAt(\DateTime $executionDate);

    /**
     * Schedules built task and returns it.
     *
     * @return TaskInterface
     */
    public function schedule();
}

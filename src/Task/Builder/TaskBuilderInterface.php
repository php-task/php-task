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
     * @param \DateTimeImmutable $firstExecution
     * @param \DateTimeImmutable $lastExecution
     *
     * @return $this
     */
    public function hourly(\DateTimeImmutable $firstExecution = null, \DateTimeImmutable $lastExecution = null);

    /**
     * Use daily interval.
     *
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return $this
     */
    public function daily(\DateTimeImmutable $start = null, \DateTimeImmutable $end = null);

    /**
     * Use weekly interval.
     *
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return $this
     */
    public function weekly(\DateTimeImmutable $start = null, \DateTimeImmutable $end = null);

    /**
     * Use monthly interval.
     *
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return $this
     */
    public function monthly(\DateTimeImmutable $start = null, \DateTimeImmutable $end = null);

    /**
     * Use yearly interval.
     *
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return $this
     */
    public function yearly(\DateTimeImmutable $start = null, \DateTimeImmutable $end = null);

    /**
     * Use given cron-interval.
     *
     * @param string $cronExpression
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     *
     * @return $this
     */
    public function cron($cronExpression, \DateTimeImmutable $start = null, \DateTimeImmutable $end = null);

    /**
     * Set execution-date.
     *
     * @param \DateTimeImmutable $executionDate
     *
     * @return $this
     */
    public function executeAt(\DateTimeImmutable $executionDate);

    /**
     * Schedules built task and returns it.
     *
     * @return TaskInterface
     */
    public function schedule();
}

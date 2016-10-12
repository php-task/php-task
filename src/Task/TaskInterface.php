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
 * Interface for task.
 */
interface TaskInterface
{
    /**
     * Returns uuid.
     *
     * @return string
     */
    public function getUuid();

    /**
     * Returns task-name.
     *
     * @return string
     */
    public function getHandlerClass();

    /**
     * Returns workload.
     *
     * @return \Serializable|string
     */
    public function getWorkload();

    /**
     * Returns interval.
     *
     * @return CronExpression
     */
    public function getInterval();

    /**
     * Returns first-execution date-time.
     *
     * @return \DateTime
     */
    public function getFirstExecution();

    /**
     * Set first-execution.
     *
     * @param \DateTime $firstExecution
     *
     * @return $this
     */
    public function setFirstExecution(\DateTime $firstExecution);

    /**
     * Returns first-execution date-time.
     *
     * @return \DateTime
     */
    public function getLastExecution();

    /**
     * Set interval.
     *
     * @param CronExpression $interval
     * @param \DateTime $firstExecution null means for "now"
     * @param \DateTime $lastExecution null means forever
     *
     * @return $this
     */
    public function setInterval(CronExpression $interval, \DateTime $firstExecution = null, \DateTime $lastExecution = null);
}

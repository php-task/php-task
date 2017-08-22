<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Execution;

use Task\TaskInterface;

/**
 * Interface for task-execution.
 */
interface TaskExecutionInterface
{
    /**
     * Returns uuid.
     *
     * @return string
     */
    public function getUuid();

    /**
     * Returns task.
     *
     * @return TaskInterface
     */
    public function getTask();

    /**
     * Returns workload.
     *
     * @return \Serializable|string
     */
    public function getWorkload();

    /**
     * Returns handler-class.
     *
     * @return string
     */
    public function getHandlerClass();

    /**
     * Returns schedule-time.
     *
     * @return \DateTime
     */
    public function getScheduleTime();

    /**
     * Returns start-time.
     *
     * @return \DateTime
     */
    public function getStartTime();

    /**
     * Returns end-time.
     *
     * @return \DateTime
     */
    public function getEndTime();

    /**
     * Returns duration.
     *
     * @return float
     */
    public function getDuration();

    /**
     * Returns status.
     *
     * @return string
     */
    public function getStatus();

    /**
     * Returns result.
     *
     * @return \Serializable|string
     */
    public function getResult();

    /**
     * Returns exception.
     *
     * @return string
     */
    public function getException();

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Set result.
     *
     * @param string|null|\Serializable $result
     *
     * @return $this
     */
    public function setResult($result);

    /**
     * Set exception.
     *
     * @param string $exception
     *
     * @return $this
     */
    public function setException($exception);

    /**
     * Set start-time.
     *
     * @param $startTime
     *
     * @return $this
     */
    public function setStartTime(\DateTime $startTime);

    /**
     * Set end-time.
     *
     * @param \DateTime $endTime
     *
     * @return $this
     */
    public function setEndTime(\DateTime $endTime);

    /**
     * Set duration.
     *
     * @param float $duration
     *
     * @return $this
     */
    public function setDuration($duration);

    /**
     * Returns amount of attempts to pass this execution.
     *
     * @return int
     */
    public function getAttempts();

    /**
     * Reset execution to retry after failed run.
     *
     * @return $this
     */
    public function reset();

    /**
     * Increments amount of attempts to pass this execution.
     *
     * @return $this
     */
    public function incrementAttempts();
}

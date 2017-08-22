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

use Ramsey\Uuid\Uuid;
use Task\TaskInterface;
use Task\TaskStatus;

/**
 * Single task-execution.
 */
class TaskExecution implements TaskExecutionInterface
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var \Serializable|string
     */
    protected $workload;

    /**
     * @var string
     */
    protected $handlerClass;

    /**
     * @var \DateTime
     */
    protected $scheduleTime;

    /**
     * @var \DateTime
     */
    protected $startTime;

    /**
     * @var \DateTime
     */
    protected $endTime;

    /**
     * @var float
     */
    protected $duration;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string|\Serializable
     */
    protected $result;

    /**
     * @var string
     */
    protected $exception;

    /**
     * @var int
     */
    protected $attempts = 1;

    /**
     * @param TaskInterface $task
     * @param $handlerClass
     * @param \DateTime $scheduleTime
     * @param string|\Serializable $workload
     * @param string $uuid
     */
    public function __construct(
        TaskInterface $task,
        $handlerClass,
        \DateTime $scheduleTime,
        $workload = null,
        $uuid = null
    ) {
        $this->uuid = $uuid ?: Uuid::uuid4()->toString();
        $this->task = $task;
        $this->handlerClass = $handlerClass;
        $this->scheduleTime = $scheduleTime;
        $this->workload = $workload;
    }

    /**
     * {@inheritdoc}
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkload()
    {
        return $this->workload;
    }

    /**
     * {@inheritdoc}
     */
    public function getHandlerClass()
    {
        return $this->handlerClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getScheduleTime()
    {
        return $this->scheduleTime;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndTime(\DateTime $endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * {@inheritdoc}
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setException($exception)
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->startTime = null;
        $this->endTime = null;
        $this->result = null;
        $this->exception = null;
        $this->status = TaskStatus::PLANNED;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementAttempts()
    {
        ++$this->attempts;

        return $this;
    }
}

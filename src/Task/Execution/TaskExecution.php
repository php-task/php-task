<?php

namespace Task\Execution;

use Ramsey\Uuid\Uuid;
use Task\TaskInterface;

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
     * @var \Exception
     */
    protected $exception;

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
    public function setException(\Exception $exception)
    {
        $this->exception = $exception;

        return $this;
    }
}

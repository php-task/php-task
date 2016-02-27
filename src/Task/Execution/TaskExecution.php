<?php

namespace Task\Execution;

use Task\TaskInterface;

class TaskExecution implements TaskExecutionInterface
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var TaskInterface
     */
    private $task;

    /**
     * @var \Serializable|string
     */
    private $workload;

    /**
     * @var string
     */
    private $handlerClass;

    /**
     * @var \DateTime
     */
    private $scheduleTime;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var float
     */
    private $duration;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string|\Serializable
     */
    private $result;

    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(TaskInterface $task, $handlerClass, \DateTime $scheduleTime, $workload = null)
    {
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

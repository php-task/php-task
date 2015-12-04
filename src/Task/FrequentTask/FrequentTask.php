<?php

namespace Task\FrequentTask;

use Task\TaskInterface;

abstract class FrequentTask implements FrequentTaskInterface
{
    /**
     * @var TaskInterface
     */
    private $task;

    /**
     * @var \DateTime
     */
    protected $start;

    /**
     * @var \DateTime
     */
    protected $end;

    public function __construct(TaskInterface $task, \DateTime $start, \DateTime $end = null)
    {
        $this->task = $task;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaskName()
    {
        return $this->task->getTaskName();
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkload()
    {
        return $this->task->getWorkload();
    }

    /**
     * {@inheritdoc}
     */
    public function isCompleted()
    {
        return $this->task->getWorkload();
    }

    /**
     * {@inheritdoc}
     */
    public function setCompleted()
    {
        $this->task->setCompleted();
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->task->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->task->setResult($result);
    }
    public function getExecutionDate()
    {
        return $this->task->getExecutionDate();
    }

    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->task->setExecutionDate($executionDate);
    }
}
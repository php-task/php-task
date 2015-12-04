<?php

namespace Task;

class Task implements TaskInterface
{
    /**
     * @var string
     */
    private $taskName;

    /**
     * @var string|\Serializable
     */
    private $workload;

    /**
     * @var \DateTime
     */
    private $executionDate;

    /**
     * @var bool
     */
    private $completed;

    /**
     * @var string|\Serializable
     */
    private $result;

    public function __construct($taskName, $workload)
    {
        $this->taskName = $taskName;
        $this->workload = $workload;
        $this->executionDate = new \DateTime();
    }

    /**
     * @return string
     */
    public function getTaskName()
    {
        return $this->taskName;
    }

    /**
     * @return \Serializable|string
     */
    public function getWorkload()
    {
        return $this->workload;
    }

    /**
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     *
     */
    public function setCompleted()
    {
        $this->completed = true;
    }

    /**
     * @return \Serializable|string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param \Serializable|string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return \DateTime
     */
    public function getExecutionDate()
    {
        return $this->executionDate;
    }

    /**
     * @param \DateTime $executionDate
     */
    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->executionDate = $executionDate;
    }
}

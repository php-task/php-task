<?php

namespace Task;

interface TaskInterface
{
    /**
     * @return string
     */
    public function getTaskName();

    /**
     * @return \Serializable|string
     */
    public function getWorkload();

    /**
     * @return boolean
     */
    public function isCompleted();

    /**
     *
     */
    public function setCompleted();

    /**
     * @return \Serializable|string
     */
    public function getResult();

    /**
     * @param \Serializable|string $result
     */
    public function setResult($result);

    public function getExecutionDate();

    public function setExecutionDate(\DateTime $executionDate);
}

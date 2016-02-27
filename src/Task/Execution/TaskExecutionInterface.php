<?php

namespace Task\Execution;

use Task\TaskInterface;

interface TaskExecutionInterface
{
    /**
     * @return string
     */
    public function getUuid();

    /**
     * @return TaskInterface
     */
    public function getTask();

    /**
     * @return \Serializable|string
     */
    public function getWorkload();

    /**
     * @return string
     */
    public function getHandlerClass();

    /**
     * @return \DateTime
     */
    public function getScheduleTime();

    /**
     * @return \DateTime
     */
    public function getStartTime();

    /**
     * @return \DateTime
     */
    public function getEndTime();

    /**
     * @return float
     */
    public function getDuration();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return \Serializable|string
     */
    public function getResult();

    /**
     * @return \Exception
     */
    public function getException();

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * @param string|null|\Serializable $result
     *
     * @return $this
     */
    public function setResult($result);

    /**
     * @param \Exception $exception
     *
     * @return $this
     */
    public function setException(\Exception $exception);
}

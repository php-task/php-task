<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

/**
 * Interface for task.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
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
    public function getTaskName();

    /**
     * Returns workload.
     *
     * @return \Serializable|string
     */
    public function getWorkload();

    /**
     * Returns key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Set key.
     *
     * @param string $key
     */
    public function setKey($key);

    /**
     * Returns flag which indicates that task is completed or not.
     *
     * @return bool
     */
    public function isCompleted();

    /**
     * Sets completed flag.
     */
    public function setCompleted();

    /**
     * Returns result.
     *
     * @return \Serializable|string
     */
    public function getResult();

    /**
     * Set result.
     *
     * @param \Serializable|string $result
     */
    public function setResult($result);

    /**
     * Returns execution date.
     *
     * @return \DateTime
     */
    public function getExecutionDate();

    /**
     * Set execution date.
     *
     * @param \DateTime $executionDate
     */
    public function setExecutionDate(\DateTime $executionDate);

    /**
     * @return TaskExecution[]
     */
    public function getExecutions();

    /**
     * @param TaskExecution $execution
     */
    public function addExecution(TaskExecution $execution);

    /**
     * @return TaskExecution
     */
    public function getLastExecution();
}

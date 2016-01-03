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

use Ramsey\Uuid\Uuid;

/**
 * Task contains name and workload to run with a handler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class Task implements TaskInterface
{
    /**
     * @var string
     */
    private $uuid;

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
    private $completed = false;

    /**
     * @var string|\Serializable
     */
    private $result;

    public function __construct($taskName, $workload, $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::uuid4()->toString();
        $this->taskName = $taskName;
        $this->workload = $workload;
        $this->executionDate = new \DateTime();
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
    public function getTaskName()
    {
        return $this->taskName;
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
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompleted()
    {
        $this->completed = true;
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
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getExecutionDate()
    {
        return $this->executionDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->executionDate = $executionDate;
    }
}

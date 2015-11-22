<?php

namespace Task\Scheduler;

use Serializable;

/**
 * Default task implementation.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class Task implements TaskInterface
{
    /**
     * @var string|Serializable
     */
    private $workload;

    /**
     * @var string|Serializable
     */
    private $result;

    /**
     * @var bool
     */
    private $completed = false;

    public function __construct($workload)
    {
        $this->workload = $workload;
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
    public function setCompleted()
    {
        $this->completed = true;
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
}

<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\FrequentTask;

use Task\TaskInterface;

/**
 * Extends Event with frequent operations.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
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
        $this->start = $start;
        $this->end = $end;
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
    public function getKey()
    {
        return $this->task->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function setKey($key)
    {
        $this->task->setKey($key);
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
        return $this->task->isCompleted();
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

    /**
     * {@inheritdoc}
     */
    public function getExecutionDate()
    {
        return $this->task->getExecutionDate();
    }

    /**
     * {@inheritdoc}
     */
    public function setExecutionDate(\DateTime $executionDate)
    {
        $this->task->setExecutionDate($executionDate);
    }

    /**
     * {@inheritdoc}
     */
    public function getUuid()
    {
        return $this->task->getUuid();
    }

    /**
     * {@inheritdoc}
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * {@inheritdoc}
     */
    public function getEnd()
    {
        return $this->end;
    }
}

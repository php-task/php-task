<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

use Cron\CronExpression;
use Symfony\Component\Uid\Uuid;

/**
 * Task information.
 */
class Task implements TaskInterface
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $handlerClass;

    /**
     * @var string
     */
    protected $workload;

    /**
     * @var CronExpression
     */
    protected $interval;

    /**
     * @var \DateTimeImmutable
     */
    protected $firstExecution;

    /**
     * @var \DateTimeImmutable
     */
    protected $lastExecution;

    /**
     * @param string $handlerClass
     * @param string|\Serializable $workload
     * @param string $uuid
     */
    public function __construct($handlerClass, $workload = null, $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::v4()->toRfc4122();
        $this->handlerClass = $handlerClass;
        $this->workload = @\serialize($workload);

        $this->firstExecution = new \DateTimeImmutable();
        $this->lastExecution = new \DateTimeImmutable();
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
    public function getHandlerClass()
    {
        return $this->handlerClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkload()
    {
        return @\unserialize($this->workload);
    }

    /**
     * {@inheritdoc}
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstExecution()
    {
        return $this->firstExecution;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstExecution(\DateTimeImmutable $firstExecution)
    {
        $this->firstExecution = $firstExecution;
        $this->lastExecution = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastExecution()
    {
        return $this->lastExecution;
    }

    /**
     * {@inheritdoc}
     */
    public function setInterval(
        CronExpression $interval,
        \DateTimeImmutable $firstExecution = null,
        \DateTimeImmutable $lastExecution = null
    ) {
        $this->interval = $interval;
        $this->firstExecution = $firstExecution ?: new \DateTimeImmutable();
        $this->lastExecution = $lastExecution;

        return $this;
    }
}

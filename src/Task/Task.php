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

use Cron\CronExpression;
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
    protected $uuid;

    /**
     * @var string
     */
    protected $handlerClass;

    /**
     * @var string|\Serializable
     */
    protected $workload;

    /**
     * @var CronExpression
     */
    protected $interval;

    /**
     * @var \DateTime
     */
    protected $firstExecution;

    /**
     * @var \DateTime
     */
    protected $lastExecution;

    public function __construct($handlerClass, $workload = null, $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::uuid4()->toString();
        $this->handlerClass = $handlerClass;
        $this->workload = $workload;

        $this->firstExecution = new \DateTime();
        $this->lastExecution = new \DateTime();
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
        return $this->workload;
    }

    /**
     * {@inheritdoc}
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @return \DateTime
     */
    public function getFirstExecution()
    {
        return $this->firstExecution;
    }

    /**
     * @return \DateTime
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
        \DateTime $firstExecution = null,
        \DateTime $lastExecution = null
    ) {
        $this->interval = $interval;
        $this->firstExecution = $firstExecution ?: new \DateTime();
        $this->lastExecution = $lastExecution;
    }
}

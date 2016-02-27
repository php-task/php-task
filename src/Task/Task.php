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
    private $uuid;

    /**
     * @var string
     */
    private $handlerClass;

    /**
     * @var string|\Serializable
     */
    private $workload;

    /**
     * @var CronExpression
     */
    private $interval;

    /**
     * @var \DateTime
     */
    private $firstExecution;

    /**
     * @var \DateTime
     */
    private $lastExecution;

    public function __construct($handlerClass, $workload = null, $uuid = null)
    {
        $this->uuid = $uuid ?: Uuid::uuid4()->toString();
        $this->handlerClass = $handlerClass;
        $this->workload = $workload;
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
    public function setInterval($interval, \DateTime $firstExecution = null, \DateTime $lastExecution = null)
    {
        $this->interval = $interval;
        $this->firstExecution = $firstExecution ?: new \DateTime();
        $this->lastExecution = $lastExecution;
    }
}

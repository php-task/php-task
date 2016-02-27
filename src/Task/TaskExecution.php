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

class TaskExecution
{
    /**
     * @var int
     */
    private $startedAt;

    /**
     * @var int
     */
    private $finishedAt;

    public function __construct($startedAt, $finishedAt)
    {
        $this->startedAt = $startedAt;
        $this->finishedAt = $finishedAt;
    }

    /**
     * @return float
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAtAsDateTime()
    {
        return $this->getDateTime($this->startedAt);
    }

    /**
     * @return float
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }

    /**
     * @return \DateTime
     */
    public function getFinishedAtAsDateTime()
    {
        return $this->getDateTime($this->startedAt);
    }

    /**
     * Returns duration of execution in microseconds
     *
     * @return int
     */
    public function getExecutionDuration()
    {
        return $this->finishedAt - $this->startedAt;
    }

    /**
     * Returns microtime as datetime.
     *
     * @param float $microTime
     *
     * @return \DateTime
     */
    private function getDateTime($microTime)
    {
        return \DateTime::createFromFormat('U.u', $microTime);
    }
}

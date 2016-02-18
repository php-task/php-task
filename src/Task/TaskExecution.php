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
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @return \DateTime
     */
    public function getFinishedAt()
    {
        return $this->finishedAt;
    }
}

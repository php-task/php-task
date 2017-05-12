<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Lock\Exception;

use Task\Execution\TaskExecutionInterface;

/**
 * Will be thrown when a conflict is detected:
 *  + Already acquired execution was acquired.
 *  + Not acquired execution was released.
 *  + Not acquired execution was refreshed.
 */
class LockConflictException extends \Exception
{
    /**
     * @var TaskExecutionInterface
     */
    private $execution;

    /**
     * @param TaskExecutionInterface $execution
     */
    public function __construct(TaskExecutionInterface $execution)
    {
        $this->execution = $execution;
    }

    /**
     * Returns execution.
     *
     * @return TaskExecutionInterface
     */
    public function getExecution()
    {
        return $this->execution;
    }
}

<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Executor;

use Task\Execution\TaskExecutionInterface;

/**
 * Interface for task-executor.
 */
interface ExecutorInterface
{
    /**
     * Executes given task.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return mixed
     *
     * @throws RetryException indicates that the current run should by retried
     * @throws FailedException indicates that the current run was failed
     */
    public function execute(TaskExecutionInterface $execution);
}

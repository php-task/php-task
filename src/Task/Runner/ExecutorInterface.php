<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Runner;

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
     */
    public function execute(TaskExecutionInterface $execution);
}

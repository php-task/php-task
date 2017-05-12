<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Lock;

use Task\Execution\TaskExecutionInterface;

/**
 * Interface for lock strategy.
 */
interface StrategyInterface
{
    /**
     * Returns key for given execution.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return string
     */
    public function getKey(TaskExecutionInterface $execution);
}

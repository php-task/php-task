<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Lock\Strategy;

use Task\Execution\TaskExecutionInterface;
use Task\Lock\StrategyInterface;

/**
 * Returns handler-class as lock key.
 */
class HandlerClassStrategy implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey(TaskExecutionInterface $execution)
    {
        return $execution->getHandlerClass();
    }
}

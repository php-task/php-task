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

use Task\Handler\TaskHandlerInterface;

/**
 * Handler which implements this interface locks other executions during run.
 */
interface LockingTaskHandlerInterface extends TaskHandlerInterface
{
    /**
     * Returns lock-key which defines the locked resources.
     *
     * @param \Serializable|string $workload
     *
     * @return string
     */
    public function getLockKey($workload);
}

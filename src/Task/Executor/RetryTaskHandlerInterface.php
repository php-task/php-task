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

/**
 * Indicates that this handler can be retried.
 */
interface RetryTaskHandlerInterface
{
    /**
     * Returns maximum attempts to pass tasks with this handler.
     *
     * @return int
     */
    public function getMaximumAttempts();
}

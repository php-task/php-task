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
use Task\Lock\Exception\LockConflictException;

/**
 * Interface for locking-mechanism.
 */
interface LockInterface
{
    /**
     * Acquires the lock of given execution.
     * If the lock is already acquired an exception will be raised.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return bool
     *
     * @throws LockConflictException
     */
    public function acquire(TaskExecutionInterface $execution);

    /**
     * Increase the duration of an acquired lock for given execution.
     * If the lock is not acquired an exception will be raised.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return bool
     *
     * @throws LockConflictException
     */
    public function refresh(TaskExecutionInterface $execution);

    /**
     * Release the lock for given execution.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return bool
     *
     * @throws LockConflictException
     */
    public function release(TaskExecutionInterface $execution);

    /**
     * Returns whether or not the lock for given execution is acquired.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return bool
     */
    public function isAcquired(TaskExecutionInterface $execution);
}

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

use Task\Lock\Exception\LockAlreadyAcquiredException;
use Task\Lock\Exception\LockNotAcquiredException;

/**
 * Interface for locking-mechanism.
 */
interface LockInterface
{
    /**
     * Acquires the lock of given key.
     * If the lock is already acquired an exception will be raised.
     *
     * @param string $key
     *
     * @return bool
     *
     * @throws LockAlreadyAcquiredException
     */
    public function acquire($key);

    /**
     * Increase the duration of an acquired lock for given key.
     * If the lock is not acquired an exception will be raised.
     *
     * @param string $key
     *
     * @return bool
     *
     * @throws LockNotAcquiredException
     */
    public function refresh($key);

    /**
     * Release the lock for given key.
     *
     * @param string $key
     *
     * @return bool
     *
     * @throws LockNotAcquiredException
     */
    public function release($key);

    /**
     * Returns whether or not the lock for given key is acquired.
     *
     * @param string $key
     *
     * @return bool
     */
    public function isAcquired($key);
}

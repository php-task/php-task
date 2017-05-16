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

/**
 * Interface for lock storage.
 */
interface LockStorageInterface
{
    /**
     * Stores the lock.
     * If the lock already exists the ttl will be increased.
     *
     * @param string $key
     * @param int $ttl
     *
     * @return bool
     */
    public function save($key, $ttl);

    /**
     * Removes the lock.
     * If the lock not exists it will be ignored.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete($key);

    /**
     * Returns whether or not the lock exists in the storage.
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);
}

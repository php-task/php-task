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
 * Manages locks.
 */
class Lock implements LockInterface
{
    /**
     * @var LockStorageInterface
     */
    private $storage;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @param LockStorageInterface $storage
     * @param int $ttl
     */
    public function __construct(LockStorageInterface $storage, $ttl = 300)
    {
        $this->storage = $storage;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function acquire($key)
    {
        $this->assertNotAcquired($key);

        return $this->storage->save($key, $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function refresh($key)
    {
        $this->assertAcquired($key);

        return $this->storage->save($key, $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function release($key)
    {
        $this->assertAcquired($key);

        return $this->storage->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function isAcquired($key)
    {
        return $this->storage->exists($key);
    }

    /**
     * Throw exception if the given key is not acquired.
     *
     * @param string $key
     *
     * @throws LockNotAcquiredException
     */
    private function assertAcquired($key)
    {
        if ($this->isAcquired($key)) {
            return;
        }

        throw new LockNotAcquiredException($key);
    }

    /**
     * Throw exception if the given key is acquired.
     *
     * @param string $key
     *
     * @throws LockAlreadyAcquiredException
     */
    private function assertNotAcquired($key)
    {
        if (!$this->isAcquired($key)) {
            return;
        }

        throw new LockAlreadyAcquiredException($key);
    }
}

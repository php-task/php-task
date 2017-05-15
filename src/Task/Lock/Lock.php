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

use Task\Lock\Exception\LockConflictException;

/**
 * Manages locks.
 */
class Lock implements LockInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @param StorageInterface $storage
     * @param int $ttl
     */
    public function __construct(StorageInterface $storage, $ttl = 300)
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
     * @throws LockConflictException
     */
    private function assertAcquired($key)
    {
        if ($this->isAcquired($key)) {
            return;
        }

        throw new LockConflictException($key);
    }

    /**
     * Throw exception if the given key is acquired.
     *
     * @param string $key
     *
     * @throws LockConflictException
     */
    private function assertNotAcquired($key)
    {
        if (!$this->isAcquired($key)) {
            return;
        }

        throw new LockConflictException($key);
    }
}

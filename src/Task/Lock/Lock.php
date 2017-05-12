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
 * Manages locks for executions.
 */
class Lock implements LockInterface
{
    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @param StrategyInterface $strategy
     * @param StorageInterface $storage
     * @param int $ttl
     */
    public function __construct(StrategyInterface $strategy, StorageInterface $storage, $ttl = 300)
    {
        $this->strategy = $strategy;
        $this->storage = $storage;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function acquire(TaskExecutionInterface $execution)
    {
        $this->assertNotAcquired($execution);

        return $this->storage->save($this->strategy->getKey($execution), $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function refresh(TaskExecutionInterface $execution)
    {
        $this->assertAcquired($execution);

        return $this->storage->save($this->strategy->getKey($execution), $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    public function release(TaskExecutionInterface $execution)
    {
        $this->assertAcquired($execution);

        return $this->storage->delete($this->strategy->getKey($execution));
    }

    /**
     * {@inheritdoc}
     */
    public function isAcquired(TaskExecutionInterface $execution)
    {
        return $this->storage->exists($this->strategy->getKey($execution));
    }

    /**
     * Throw exception if the given execution is not acquired.
     *
     * @param TaskExecutionInterface $execution
     *
     * @throws LockConflictException
     */
    private function assertAcquired(TaskExecutionInterface $execution)
    {
        if ($this->isAcquired($execution)) {
            return;
        }

        throw new LockConflictException($execution);
    }

    /**
     * Throw exception if the given execution is acquired.
     *
     * @param TaskExecutionInterface $execution
     *
     * @throws LockConflictException
     */
    private function assertNotAcquired(TaskExecutionInterface $execution)
    {
        if (!$this->isAcquired($execution)) {
            return;
        }

        throw new LockConflictException($execution);
    }
}

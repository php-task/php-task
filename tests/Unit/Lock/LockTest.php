<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Lock;

use Task\Execution\TaskExecutionInterface;
use Task\Lock\Lock;
use Task\Lock\LockInterface;
use Task\Lock\StorageInterface;
use Task\Lock\StrategyInterface;

class LockTest extends \PHPUnit_Framework_TestCase
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
    private $ttl = 300;

    /**
     * @var LockInterface
     */
    private $lock;

    /**
     * @var TaskExecutionInterface
     */
    private $execution;

    /**
     * @var string
     */
    private $key = 'test-key';

    protected function setUp()
    {
        $this->strategy = $this->prophesize(StrategyInterface::class);
        $this->storage = $this->prophesize(StorageInterface::class);
        $this->execution = $this->prophesize(TaskExecutionInterface::class);

        $this->lock = new Lock($this->strategy->reveal(), $this->storage->reveal(), $this->ttl);

        $this->strategy->getKey($this->execution->reveal())->willReturn($this->key);
    }

    public function testAcquire()
    {
        $this->storage->exists($this->key)->willReturn(false);
        $this->storage->save($this->key, $this->ttl)->shouldBeCalled()->willReturn(true);

        $this->assertTrue($this->lock->acquire($this->execution->reveal()));
    }

    /**
     * @expectedException \Task\Lock\Exception\LockConflictException
     */
    public function testAcquireAlreadyAcquired()
    {
        $this->storage->exists($this->key)->willReturn(true);
        $this->storage->save($this->key, $this->ttl)->shouldNotBeCalled();

        $this->lock->acquire($this->execution->reveal());
    }

    public function testRefresh()
    {
        $this->storage->exists($this->key)->willReturn(true);
        $this->storage->save($this->key, $this->ttl)->shouldBeCalled()->willReturn(true);

        $this->assertTrue($this->lock->refresh($this->execution->reveal()));
    }

    /**
     * @expectedException \Task\Lock\Exception\LockConflictException
     */
    public function testRefreshNotAcquired()
    {
        $this->storage->exists($this->key)->willReturn(false);
        $this->storage->save($this->key, $this->ttl)->shouldNotBeCalled();

        $this->assertTrue($this->lock->refresh($this->execution->reveal()));
    }

    public function testRelease()
    {
        $this->storage->exists($this->key)->willReturn(true);
        $this->storage->delete($this->key)->shouldBeCalled()->willReturn(true);

        $this->assertTrue($this->lock->release($this->execution->reveal()));
    }

    /**
     * @expectedException \Task\Lock\Exception\LockConflictException
     */
    public function testReleaseNotAcquired()
    {
        $this->storage->exists($this->key)->willReturn(false);
        $this->storage->delete($this->key)->shouldNotBeCalled();

        $this->assertTrue($this->lock->release($this->execution->reveal()));
    }
}

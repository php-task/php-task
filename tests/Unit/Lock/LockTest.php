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

use PHPUnit\Framework\TestCase;
use Task\Lock\Exception\LockConflictException;
use Task\Lock\Lock;
use Task\Lock\LockInterface;
use Task\Lock\LockStorageInterface;

class LockTest extends TestCase
{
    /**
     * @var LockStorageInterface
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
     * @var string
     */
    private $key = 'test-key';

    protected function setUp()
    {
        $this->storage = $this->prophesize(LockStorageInterface::class);

        $this->lock = new Lock($this->storage->reveal(), $this->ttl);
    }

    public function testAcquire()
    {
        $this->storage->exists($this->key)->willReturn(false);
        $this->storage->save($this->key, $this->ttl)->shouldBeCalled()->willReturn(true);

        $this->assertTrue($this->lock->acquire($this->key));
    }

    public function testAcquireAlreadyAcquired()
    {
        $this->expectException(LockConflictException::class);

        $this->storage->exists($this->key)->willReturn(true);
        $this->storage->save($this->key, $this->ttl)->shouldNotBeCalled();

        $this->lock->acquire($this->key);
    }

    public function testRefresh()
    {
        $this->storage->exists($this->key)->willReturn(true);
        $this->storage->save($this->key, $this->ttl)->shouldBeCalled()->willReturn(true);

        $this->assertTrue($this->lock->refresh($this->key));
    }

    public function testRefreshNotAcquired()
    {
        $this->expectException(LockConflictException::class);

        $this->storage->exists($this->key)->willReturn(false);
        $this->storage->save($this->key, $this->ttl)->shouldNotBeCalled();

        $this->assertTrue($this->lock->refresh($this->key));
    }

    public function testRelease()
    {
        $this->storage->exists($this->key)->willReturn(true);
        $this->storage->delete($this->key)->shouldBeCalled()->willReturn(true);

        $this->assertTrue($this->lock->release($this->key));
    }

    public function testReleaseNotAcquired()
    {
        $this->expectException(LockConflictException::class);

        $this->storage->exists($this->key)->willReturn(false);
        $this->storage->delete($this->key)->shouldNotBeCalled();

        $this->assertTrue($this->lock->release($this->key));
    }
}

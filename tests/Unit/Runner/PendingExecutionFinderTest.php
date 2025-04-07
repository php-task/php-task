<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Runner;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Uid\Uuid;
use Task\Execution\TaskExecutionInterface;
use Task\Handler\TaskHandlerFactoryInterface;
use Task\Handler\TaskHandlerInterface;
use Task\Lock\LockingTaskHandlerInterface;
use Task\Lock\LockInterface;
use Task\Runner\PendingExecutionFinder;
use Task\Storage\TaskExecutionRepositoryInterface;

class PendingExecutionFinderTest extends TestCase
{
    use ProphecyTrait;

    const HANDLER = 'AppBundle\\Handler\\TestHandler';

    const LOCKING_HANDLER = 'AppBundle\\Handler\\LockingTestHandler';

    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $taskExecutionRepository;

    /**
     * @var TaskHandlerFactoryInterface
     */
    private $taskHandlerFactory;

    /**
     * @var LockInterface
     */
    private $lock;

    /**
     * @var PendingExecutionFinder
     */
    private $finder;

    /**
     * @var TaskHandlerInterface
     */
    private $handler;

    /**
     * @var LockingTaskHandlerInterface
     */
    private $lockingHandler;

    protected function setUp(): void
    {
        $this->taskExecutionRepository = $this->prophesize(TaskExecutionRepositoryInterface::class);
        $this->taskHandlerFactory = $this->prophesize(TaskHandlerFactoryInterface::class);
        $this->lock = $this->prophesize(LockInterface::class);

        $this->finder = new PendingExecutionFinder(
            $this->taskExecutionRepository->reveal(), $this->taskHandlerFactory->reveal(), $this->lock->reveal()
        );

        $this->handler = $this->prophesize(TaskHandlerInterface::class);
        $this->lockingHandler = $this->prophesize(LockingTaskHandlerInterface::class);
        $this->lockingHandler->getLockKey('test-workload')->willReturn(self::LOCKING_HANDLER);

        $this->taskHandlerFactory->create(self::HANDLER)->willReturn($this->handler->reveal());
        $this->taskHandlerFactory->create(self::LOCKING_HANDLER)->willReturn($this->lockingHandler->reveal());
    }

    public function testFind()
    {
        $executions = [
            $this->createExecution(),
            $this->createExecution(),
        ];

        $this->initializeRepository($executions);

        $result = [];
        foreach ($this->finder->find() as $execution) {
            $result[] = $execution;
        }

        $this->assertCount(2, $result);
        $this->assertEquals($executions, $result);
    }

    public function testFindLocked()
    {
        $executions = [
            $this->createExecution(),
            $this->createExecution(self::LOCKING_HANDLER),
            $this->createExecution(),
        ];

        $this->lock->isAcquired(self::LOCKING_HANDLER)->willReturn(true);

        $this->initializeRepository($executions, [false, true, false]);

        $result = [];
        foreach ($this->finder->find() as $execution) {
            $result[] = $execution;
        }

        $this->assertCount(2, $result);
        $this->assertEquals($executions[0], $result[0]);
        $this->assertEquals($executions[2], $result[1]);
    }

    public function testFindNotLocked()
    {
        $executions = [
            $this->createExecution(self::LOCKING_HANDLER),
            $this->createExecution(),
        ];

        $this->lock->isAcquired(self::LOCKING_HANDLER)->willReturn(false);
        $this->lock->acquire(self::LOCKING_HANDLER)->shouldBeCalled()->willReturn(true);
        $this->lock->release(self::LOCKING_HANDLER)->shouldBeCalled();

        $this->initializeRepository($executions, [false, false]);

        $result = [];
        foreach ($this->finder->find() as $execution) {
            $result[] = $execution;
        }

        $this->assertCount(2, $result);
        $this->assertEquals($executions, $result);
    }

    public function testFindAcquireNotPossible()
    {
        $executions = [
            $this->createExecution(self::LOCKING_HANDLER),
            $this->createExecution(),
        ];

        $this->lock->isAcquired(self::LOCKING_HANDLER)->willReturn(false);
        $this->lock->acquire(self::LOCKING_HANDLER)->shouldBeCalled()->willReturn(false);
        $this->lock->release(self::LOCKING_HANDLER)->shouldNotBeCalled();

        $this->initializeRepository($executions, [true, false]);

        $result = [];
        foreach ($this->finder->find() as $execution) {
            $result[] = $execution;
        }

        $this->assertCount(1, $result);
        $this->assertEquals($executions[1], $result[0]);
    }

    private function createExecution($handler = self::HANDLER)
    {
        $execution = $this->prophesize(TaskExecutionInterface::class);
        $execution->getUuid()->willReturn(Uuid::v4()->toRfc4122());
        $execution->getHandlerClass()->willReturn($handler);
        $execution->getWorkload()->willReturn('test-workload');

        return $execution->reveal();
    }

    private function initializeRepository(array $executions, array $locked = [], array $lockedUuids = [])
    {
        $that = $this;

        $this->taskExecutionRepository->findNextScheduled(Argument::type(\DateTime::class), $lockedUuids)->will(
            function () use ($that, $executions, $locked, $lockedUuids) {
                $execution = array_shift($executions);

                if (array_shift($locked)) {
                    $lockedUuids[] = $execution->getUuid();
                }

                $that->initializeRepository($executions, $locked, $lockedUuids);

                return $execution;
            }
        );
    }
}

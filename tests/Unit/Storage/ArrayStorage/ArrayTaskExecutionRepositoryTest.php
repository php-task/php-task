<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Storage\ArrayStorage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Task\Execution\TaskExecution;
use Task\Execution\TaskExecutionInterface;
use Task\Storage\ArrayStorage\ArrayTaskExecutionRepository;
use Task\Task;
use Task\TaskStatus;

/**
 * Tests for ArrayTaskExecutionRepository.
 */
class ArrayTaskExecutionRepositoryTest extends TestCase
{
    public function testSave()
    {
        $taskExecutionCollection = $this->prophesize(Collection::class);
        $taskExecutionRepository = new ArrayTaskExecutionRepository($taskExecutionCollection->reveal());

        $execution = $this->prophesize(TaskExecutionInterface::class);

        $taskExecutionCollection->contains($execution->reveal())->willReturn(false);
        $taskExecutionCollection->add($execution->reveal())->shouldBeCalled();

        $this->assertEquals(
            $taskExecutionRepository,
            $taskExecutionRepository->save($execution->reveal())
        );
    }

    public function testSaveExisting()
    {
        $taskExecutionCollection = $this->prophesize(Collection::class);
        $taskExecutionRepository = new ArrayTaskExecutionRepository($taskExecutionCollection->reveal());

        $execution = $this->prophesize(TaskExecutionInterface::class);

        $taskExecutionCollection->contains($execution->reveal())->willReturn(true);
        $taskExecutionCollection->add($execution->reveal())->shouldNotBeCalled();

        $this->assertEquals(
            $taskExecutionRepository,
            $taskExecutionRepository->save($execution->reveal())
        );
    }

    public function testRemove()
    {
        $taskExecutionCollection = $this->prophesize(Collection::class);
        $taskExecutionRepository = new ArrayTaskExecutionRepository($taskExecutionCollection->reveal());

        $execution = $this->prophesize(TaskExecutionInterface::class);

        $taskExecutionCollection->removeElement($execution->reveal())->shouldBeCalled();

        $this->assertEquals(
            $taskExecutionRepository,
            $taskExecutionRepository->remove($execution->reveal())
        );
    }

    public function testFindByStartTime()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'),
        ];

        foreach ($executions as $execution) {
            $execution->setStatus(TaskStatus::PLANNED);
        }

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals($executions[0], $repository->findPending($task));
    }

    public function testFindByTask()
    {
        $task1 = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $task2 = new Task(\stdClass::class, 'Test 1');
        $executions = [
            new TaskExecution($task1, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task2, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'),
            new TaskExecution($task1, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals([$executions[0], $executions[2]], $repository->findByTask($task1));
        $this->assertEquals([$executions[1]], $repository->findByTask($task2));
    }

    public function testFindByUuid()
    {
        $task1 = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task1, \stdClass::class, new \DateTime('+1 day'), 'Test 1', '123-123-123'),
            new TaskExecution($task1, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'),
            new TaskExecution($task1, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals($executions[0], $repository->findByUuid('123-123-123'));
    }

    public function testFindByTaskUuid()
    {
        $task1 = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $task2 = new Task(\stdClass::class, 'Test 1');
        $executions = [
            new TaskExecution($task1, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task2, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'),
            new TaskExecution($task1, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals([$executions[0], $executions[2]], $repository->findByTaskUuid($task1->getUuid()));
        $this->assertEquals([$executions[1]], $repository->findByTask($task2));
    }

    public function testFindPending()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
        ];

        $executions[0]->setStatus(TaskStatus::COMPLETED);
        $executions[1]->setStatus(TaskStatus::FAILED);
        $executions[2]->setStatus(TaskStatus::ABORTED);

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertNull($repository->findPending($task));
    }

    public function testFindPendingPlanned()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
        ];

        $executions[0]->setStatus(TaskStatus::COMPLETED);
        $executions[1]->setStatus(TaskStatus::FAILED);
        $executions[2]->setStatus(TaskStatus::ABORTED);
        $executions[3]->setStatus(TaskStatus::PLANNED);

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals($executions[3], $repository->findPending($task));
    }

    public function testFindPendingStarted()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
        ];

        foreach ($executions as $execution) {
            $execution->setStatus(TaskStatus::COMPLETED);
        }
        $executions[2]->setStatus(TaskStatus::RUNNING);

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals($executions[2], $repository->findPending($task));
    }

    public function testFindAll()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $result = $repository->findAll();
        $this->assertCount(3, $result);

        $this->assertEquals($executions[0], $result[0]);
        $this->assertEquals($executions[1], $result[1]);
        $this->assertEquals($executions[2], $result[2]);
    }

    public function testFindAllPaginated()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $result = $repository->findAll(1, 2);
        $this->assertCount(2, $result);

        $this->assertEquals($executions[0], $result[0]);
        $this->assertEquals($executions[1], $result[1]);

        $result = $repository->findAll(2, 2);
        $this->assertCount(1, $result);

        $this->assertEquals($executions[2], $result[0]);
    }

    public function testFindNextScheduled()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            (new TaskExecution($task, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'))
                ->setStatus(TaskStatus::PLANNED),
            (new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'))
                ->setStatus(TaskStatus::FAILED),
            (new TaskExecution($task, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'))
                ->setStatus(TaskStatus::COMPLETED),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $result = $repository->findNextScheduled();
        $this->assertEquals($executions[0], $result);
    }
}

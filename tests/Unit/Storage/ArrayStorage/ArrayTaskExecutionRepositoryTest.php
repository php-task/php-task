<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Unit\Storage\ArrayStorage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prophecy\Argument;
use Task\Execution\TaskExecution;
use Task\Execution\TaskExecutionInterface;
use Task\Storage\ArrayStorage\ArrayTaskExecutionRepository;
use Task\Task;
use Task\TaskStatus;

/**
 * Tests for ArrayTaskExecutionRepository.
 */
class ArrayTaskExecutionRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testPersist()
    {
        $taskExecutionCollection = $this->prophesize(Collection::class);
        $taskExecutionRepository = new ArrayTaskExecutionRepository($taskExecutionCollection->reveal());

        $execution = $this->prophesize(TaskExecutionInterface::class);

        $taskExecutionCollection->add($execution->reveal())->shouldBeCalled();

        $this->assertEquals(
            $taskExecutionRepository,
            $taskExecutionRepository->persist($execution->reveal())
        );
    }

    public function testFlush()
    {
        $taskExecutionCollection = $this->prophesize(Collection::class);
        $taskExecutionRepository = new ArrayTaskExecutionRepository($taskExecutionCollection->reveal());

        $taskExecutionCollection->add(Argument::any())->shouldNotBeCalled();

        $this->assertEquals($taskExecutionRepository, $taskExecutionRepository->flush());
    }

    public function testFindByStartTime()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertEquals($executions[1], $repository->findByStartTime($task, $executions[1]->getScheduleTime()));
    }

    public function testFindByStartTimeNoResult()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 day'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'),
            new TaskExecution($task, \stdClass::class, new \DateTime('+1 hour'), 'Test 1'),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $this->assertNull($repository->findByStartTime($task, new \DateTime()));
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

    public function testFindScheduled()
    {
        $task = new Task(\stdClass::class, 'Test 1', '123-123-123');
        $executions = [
            (new TaskExecution($task, \stdClass::class, new \DateTime('1 day ago'), 'Test 1'))
                ->setStatus(TaskStatus::PLANNED),
            (new TaskExecution($task, \stdClass::class, new \DateTime('+1 minute'), 'Test 1'))
                ->setStatus(TaskStatus::FAILED),
            (new TaskExecution($task, \stdClass::class, new \DateTime('1 hour ago'), 'Test 1'))
                ->setStatus(TaskStatus::COMPLETE),
        ];

        $repository = new ArrayTaskExecutionRepository(new ArrayCollection($executions));

        $result = $repository->findScheduled();
        $this->assertCount(1, $result);

        $this->assertEquals($executions[0], $result[0]);
    }
}

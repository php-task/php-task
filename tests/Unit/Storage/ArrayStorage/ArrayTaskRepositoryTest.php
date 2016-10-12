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

use Cron\CronExpression;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Prophecy\Argument;
use Task\Storage\ArrayStorage\ArrayTaskRepository;
use Task\Task;
use Task\TaskInterface;

/**
 * Tests for ArrayTaskRepository.
 */
class ArrayTaskRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testPersist()
    {
        $collection = $this->prophesize(Collection::class);
        $repository = new ArrayTaskRepository($collection->reveal());

        $task = $this->prophesize(TaskInterface::class);

        $collection->add($task->reveal())->shouldBeCalled();

        $this->assertEquals(
            $repository,
            $repository->persist($task->reveal())
        );
    }

    public function testFlush()
    {
        $collection = $this->prophesize(Collection::class);
        $repository = new ArrayTaskRepository($collection->reveal());

        $collection->add(Argument::any())->shouldNotBeCalled();

        $this->assertEquals($repository, $repository->flush());
    }

    public function testFindAll()
    {
        $tasks = [
            new Task(\stdClass::class, 'Test 1'),
            new Task(\stdClass::class, 'Test 2'),
            new Task(\stdClass::class, 'Test 3'),
        ];

        $repository = new ArrayTaskRepository(new ArrayCollection($tasks));

        $result = $repository->findAll();
        $this->assertCount(3, $result);

        $this->assertEquals($tasks[0], $result[0]);
        $this->assertEquals($tasks[1], $result[1]);
        $this->assertEquals($tasks[2], $result[2]);
    }

    public function testFindAllPaginated()
    {
        $tasks = [
            new Task(\stdClass::class, 'Test 1'),
            new Task(\stdClass::class, 'Test 2'),
            new Task(\stdClass::class, 'Test 3'),
        ];

        $repository = new ArrayTaskRepository(new ArrayCollection($tasks));

        $result = $repository->findAll(1, 2);
        $this->assertCount(2, $result);

        $this->assertEquals($tasks[0], $result[0]);
        $this->assertEquals($tasks[1], $result[1]);

        $result = $repository->findAll(2, 2);
        $this->assertCount(1, $result);

        $this->assertEquals($tasks[2], $result[0]);
    }

    public function testFindEndBeforeNow()
    {
        $tasks = [
            (new Task(\stdClass::class, 'Test 1'))
                ->setInterval(CronExpression::factory('@daily'), new \DateTime(), new \DateTime('+1 day')),
            (new Task(\stdClass::class, 'Test 2'))
                ->setInterval(CronExpression::factory('@yearly'), new \DateTime('-2 day'), new \DateTime('-1 day')),
            (new Task(\stdClass::class, 'Test 3'))
                ->setInterval(CronExpression::factory('@monthly'), new \DateTime(), new \DateTime('+1 day')),
        ];

        $repository = new ArrayTaskRepository(new ArrayCollection($tasks));

        $result = $repository->findEndBeforeNow();
        $this->assertCount(2, $result);

        $this->assertEquals($tasks[0], $result[0]);
        $this->assertEquals($tasks[2], $result[1]);
    }
}

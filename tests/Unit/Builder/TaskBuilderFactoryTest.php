<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Builder;

use Task\Builder\TaskBuilderFactory;
use Task\Builder\TaskBuilderInterface;
use Task\TaskInterface;

/**
 * Tests for TaskBuilder.
 */
class TaskBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $task = $this->prophesize(TaskInterface::class);

        $factory = new TaskBuilderFactory();

        $this->assertInstanceOf(TaskBuilderInterface::class, $factory->createTaskBuilder($task->reveal()));
    }
}

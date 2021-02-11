<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Handler;

use PHPUnit\Framework\TestCase;
use Task\Handler\TaskHandlerFactory;
use Task\Handler\TaskHandlerInterface;
use Task\Handler\TaskHandlerNotExistsException;

/**
 * Tests for TaskHandlerFactory.
 */
class TaskHandlerFactoryTest extends TestCase
{
    public function testCreate()
    {
        $factory = new TaskHandlerFactory();

        $this->assertInstanceOf(TestHandler::class, $factory->create(TestHandler::class));
    }

    public function testCreateNotExisting()
    {
        $this->expectException(TaskHandlerNotExistsException::class);

        $factory = new TaskHandlerFactory();

        $factory->create('Not\Existing\Class');
    }
}

class TestHandler implements TaskHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($workload)
    {
        return $workload;
    }
}

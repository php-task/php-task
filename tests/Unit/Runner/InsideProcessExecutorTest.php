<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Unit\Runner;

use PHPUnit\Framework\TestCase;
use Task\Execution\TaskExecutionInterface;
use Task\Executor\FailedException;
use Task\Executor\InsideProcessExecutor;
use Task\Executor\RetryException;
use Task\Executor\RetryTaskHandlerInterface;
use Task\Handler\TaskHandlerFactoryInterface;
use Task\Handler\TaskHandlerInterface;

class InsideProcessExecutorTest extends TestCase
{
    /**
     * @var TaskHandlerFactoryInterface
     */
    private $handlerFactory;

    /**
     * @var InsideProcessExecutor
     */
    private $executor;

    protected function setUp(): void
    {
        $this->handlerFactory = $this->prophesize(TaskHandlerFactoryInterface::class);

        $this->executor = new InsideProcessExecutor($this->handlerFactory->reveal());
    }

    public function testExecute()
    {
        $executions = $this->prophesize(TaskExecutionInterface::class);
        $executions->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');
        $executions->getWorkload()->willReturn('test workload');

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->handle('test workload')->willReturn(strrev('test workload'));

        $this->handlerFactory->create('AppBundle\\Handler\\TestHandler')->willReturn($handler->reveal());

        $result = $this->executor->execute($executions->reveal());

        $this->assertEquals(strrev('test workload'), $result);
    }

    public function testExecuteException()
    {
        $this->expectException(\InvalidArgumentException::class, 'test message');

        $executions = $this->prophesize(TaskExecutionInterface::class);
        $executions->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');
        $executions->getWorkload()->willReturn('test workload');

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->handle('test workload')->willThrow(new \InvalidArgumentException('test message'));

        $this->handlerFactory->create('AppBundle\\Handler\\TestHandler')->willReturn($handler->reveal());

        $this->executor->execute($executions->reveal());
    }

    public function testExecuteExceptionRetry()
    {
        $execution = $this->prophesize(TaskExecutionInterface::class);
        $execution->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');
        $execution->getWorkload()->willReturn('test workload');

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->willImplement(RetryTaskHandlerInterface::class);
        $handler->handle('test workload')->willThrow(new \InvalidArgumentException('test message'));
        $handler->getMaximumAttempts()->willReturn(3);

        $this->handlerFactory->create('AppBundle\\Handler\\TestHandler')->willReturn($handler->reveal());

        try {
            $this->executor->execute($execution->reveal());
        } catch (RetryException $exception) {
            $this->assertInstanceOf(RetryException::class, $exception);
            $this->assertEquals(3, $exception->getMaximumAttempts());
            $this->assertInstanceOf(\InvalidArgumentException::class, $exception->getPrevious());

            return;
        }

        $this->fail('No RetryException was thrown');
    }

    public function testExecuteFailedException()
    {
        $this->expectException(\InvalidArgumentException::class, 'test message');

        $execution = $this->prophesize(TaskExecutionInterface::class);
        $execution->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');
        $execution->getWorkload()->willReturn('test workload');

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->willImplement(RetryTaskHandlerInterface::class);
        $handler->handle('test workload')->willThrow(new FailedException(new \InvalidArgumentException('test message')));
        $handler->getMaximumAttempts()->willReturn(3);

        $this->handlerFactory->create('AppBundle\\Handler\\TestHandler')->willReturn($handler->reveal());

        $this->executor->execute($execution->reveal());

        $this->fail('No RetryException was thrown');
    }
}

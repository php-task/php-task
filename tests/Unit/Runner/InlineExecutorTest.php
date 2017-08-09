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

use Task\Execution\TaskExecutionInterface;
use Task\Handler\TaskHandlerFactoryInterface;
use Task\Handler\TaskHandlerInterface;
use Task\Runner\InlineExecutor;

class InlineExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TaskHandlerFactoryInterface
     */
    private $handlerFactory;

    /**
     * @var InlineExecutor
     */
    private $inlineExecutor;

    protected function setUp()
    {
        $this->handlerFactory = $this->prophesize(TaskHandlerFactoryInterface::class);

        $this->inlineExecutor = new InlineExecutor($this->handlerFactory->reveal());
    }

    public function testExecute()
    {
        $executions = $this->prophesize(TaskExecutionInterface::class);
        $executions->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');
        $executions->getWorkload()->willReturn('test workload');

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->handle('test workload')->willReturn(strrev('test workload'));

        $this->handlerFactory->create('AppBundle\\Handler\\TestHandler')->willReturn($handler->reveal());

        $result = $this->inlineExecutor->execute($executions->reveal());

        $this->assertEquals(strrev('test workload'), $result);
    }

    public function testExecuteException()
    {
        $this->setExpectedException(\InvalidArgumentException::class, 'test message');

        $executions = $this->prophesize(TaskExecutionInterface::class);
        $executions->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');
        $executions->getWorkload()->willReturn('test workload');

        $handler = $this->prophesize(TaskHandlerInterface::class);
        $handler->handle('test workload')->willThrow(new \InvalidArgumentException('test message'));

        $this->handlerFactory->create('AppBundle\\Handler\\TestHandler')->willReturn($handler->reveal());

        $this->inlineExecutor->execute($executions->reveal());
    }
}

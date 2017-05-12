<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Tests\Unit\Lock\Strategy;

use Task\Execution\TaskExecutionInterface;
use Task\Lock\Strategy\HandlerClassStrategy;

class HandlerStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKey()
    {
        $execution = $this->prophesize(TaskExecutionInterface::class);
        $execution->getHandlerClass()->willReturn('AppBundle\\Handler\\TestHandler');

        $strategy = new HandlerClassStrategy();

        $this->assertEquals('AppBundle\\Handler\\TestHandler', $strategy->getKey($execution->reveal()));
    }
}

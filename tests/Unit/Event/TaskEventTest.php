<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Unit\Event;

use Task\Event\TaskEvent;
use Task\TaskInterface;

/**
 * Test for class TaskEvent.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class TaskEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTask()
    {
        $task = $this->prophesize(TaskInterface::class);

        $event = new TaskEvent($task->reveal());

        $this->assertEquals($task->reveal(), $event->getTask());
    }
}

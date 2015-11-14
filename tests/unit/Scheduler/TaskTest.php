<?php

namespace Unit\Scheduler;

use Task\Scheduler\Task;

/**
 * @group unit
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testGetWorkload()
    {
        $task = new Task('workload');

        $this->assertEquals('workload', $task->getWorkload());
    }
}

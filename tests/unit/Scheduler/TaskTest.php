<?php

namespace Unit\Scheduler;

use Task\Scheduler\Task;

/**
 * @group unit
 */
class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testGetter()
    {
        $task = new Task('worker-name', 'workload');

        $this->assertEquals('worker-name', $task->getWorkerName());
        $this->assertEquals('workload', $task->getWorkload());
    }
}

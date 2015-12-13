<?php

namespace Unit;

use Task\SchedulerInterface;
use Task\TaskBuilder;
use Task\TaskBuilderFactory;

class TaskBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $scheduler = $this->prophesize(SchedulerInterface::class);
        $factory = new TaskBuilderFactory();

        $result = $factory->create($scheduler->reveal(), 'test-name', 'test-workload');

        $this->assertInstanceOf(TaskBuilder::class, $result);
    }
}

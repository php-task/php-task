<?php

namespace Unit\Naming;

use Task\Naming\NamingFactory;
use Task\Scheduler\TaskInterface;
use Task\TaskRunner\WorkerInterface;

/**
 * @group unit
 */
class NamingFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFromWorker()
    {
        $namespace = 'test';
        $name = 'from-worker';

        $worker = $this->prophesize(WorkerInterface::class);
        $worker->getNamespace()->willReturn($namespace);
        $worker->getName()->willReturn($name);

        $namingFactory = new NamingFactory();
        $result = $namingFactory->fromWorker($worker->reveal());

        $this->assertEquals(sprintf('%s.%s', $namespace, $name), $result);
    }

    public function testFromTask()
    {
        $workerName = 'test.from-task';

        $task = $this->prophesize(TaskInterface::class);
        $task->getWorkerName()->willReturn($workerName);

        $namingFactory = new NamingFactory();
        $result = $namingFactory->fromTask($task->reveal());

        $this->assertEquals($workerName, $result);
    }
}

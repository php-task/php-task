<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Unit\FrequentTask;

use Task\FrequentTask\FrequentTask;
use Task\TaskInterface;

/**
 * Test for class FrequentTask.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class FrequentTaskTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTaskName()
    {
        $value = 'test.name';

        $task = $this->prophesize(TaskInterface::class);
        $task->getTaskName()->willReturn($value);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($value, $frequentTask->getTaskName());
    }

    public function testGetWorkload()
    {
        $value = 'workload';

        $task = $this->prophesize(TaskInterface::class);
        $task->getWorkload()->willReturn($value);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($value, $frequentTask->getWorkload());
    }

    public function booleanProvider()
    {
        return [[true], [false]];
    }

    /**
     * @dataProvider booleanProvider
     */
    public function testIsCompleted($value)
    {
        $task = $this->prophesize(TaskInterface::class);
        $task->isCompleted()->willReturn($value);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($value, $frequentTask->isCompleted());
    }

    public function testSetCompleted()
    {
        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);
        $frequentTask->setCompleted();

        $task->setCompleted()->shouldBeCalled();
    }

    public function testGetResult()
    {
        $value = 'result';

        $task = $this->prophesize(TaskInterface::class);
        $task->getResult()->willReturn($value);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($value, $frequentTask->getResult());
    }

    public function testSetResult()
    {
        $value = 'result';

        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);
        $frequentTask->setResult($value);

        $task->setResult($value)->shouldBeCalled();
    }

    public function testGetExecutionDate()
    {
        $value = new \DateTime();

        $task = $this->prophesize(TaskInterface::class);
        $task->getExecutionDate()->willReturn($value);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($value, $frequentTask->getExecutionDate());
    }

    public function testSetExecutionDate()
    {
        $value = new \DateTime();

        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);
        $frequentTask->setExecutionDate($value);

        $task->setExecutionDate($value)->shouldBeCalled();
    }

    public function testGetUuid()
    {
        $value = '123-123-123';

        $task = $this->prophesize(TaskInterface::class);
        $task->getUuid()->willReturn($value);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($value, $frequentTask->getUuid());
    }

    public function testGetStart()
    {
        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals($start, $frequentTask->getStart());
    }

    public function testGetEnd()
    {
        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();
        $end = new \DateTime('+1 day');

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start, $end]);

        $this->assertEquals($end, $frequentTask->getEnd());
    }

    public function testGetKey()
    {
        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();
        $task->getKey()->willReturn('test-key');

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $this->assertEquals('test-key', $frequentTask->getKey());
    }

    public function testSetKey()
    {
        $task = $this->prophesize(TaskInterface::class);
        $start = new \DateTime();
        $task->setKey('test-key')->shouldBecalledTimes(1);

        $frequentTask = $this->getMockForAbstractClass(FrequentTask::class, [$task->reveal(), $start]);

        $frequentTask->setKey('test-key');
    }
}

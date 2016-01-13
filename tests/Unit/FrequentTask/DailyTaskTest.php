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

use Prophecy\Argument;
use Task\FrequentTask\DailyTask;
use Task\Scheduler;
use Task\TaskBuilder;
use Task\TaskInterface;

/**
 * Test for class DailyTask.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class DailyTaskTest extends \PHPUnit_Framework_TestCase
{
    public function scheduleNextProvider()
    {
        return [
            [new \DateTime('2 days ago'), new \DateTime('1 day ago')],
            [new \DateTime('1 day ago'), new \DateTime('+2 days'), new \DateTime('+1 day')],
            [new \DateTime('1 day ago'), new \DateTime('+25 hours'), new \DateTime('+1 day')],
            [new \DateTime('1 day ago'), new \DateTime('+25 hours'), new \DateTime('+1 day'), 'test-key'],
        ];
    }

    /**
     * @dataProvider scheduleNextProvider
     */
    public function testScheduleNext(
        \DateTime $start,
        \DateTime $end,
        \DateTime $scheduledExecutionDate = null,
        $key = null
    ) {
        $scheduler = $this->prophesize(Scheduler::class);
        if ($scheduledExecutionDate !== null) {
            $taskBuilder = $this->prophesize(TaskBuilder::class);
            $scheduler->createTask('test-task', 'test-task: workload')->willReturn($taskBuilder->reveal());

            $taskBuilder->daily($start, $end)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setKey($key)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setExecutionDate(
                Argument::that(
                    function (\DateTime $dateTime) use ($scheduledExecutionDate) {
                        $this->assertEquals($scheduledExecutionDate->getTimestamp(), $dateTime->getTimestamp(), '', 2);

                        return true;
                    }
                )
            )->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->schedule()->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
        }

        $task = $this->prophesize(TaskInterface::class);
        $task->getTaskName()->willReturn('test-task');
        $task->getWorkload()->willReturn('test-task: workload');
        $task->getExecutionDate()->willReturn(new \DateTime());
        $task->getKey()->willReturn($key);

        $task = new DailyTask($task->reveal(), $start, $end);

        $task->scheduleNext($scheduler->reveal());
    }
}

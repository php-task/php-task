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
use Task\FrequentTask\HourlyTask;
use Task\Scheduler;
use Task\TaskBuilder;
use Task\TaskInterface;

/**
 * Test for class HourlyTask.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class HourlyTaskTest extends \PHPUnit_Framework_TestCase
{
    public function scheduleNextProvider()
    {
        return [
            [new \DateTime('2 hours ago'), new \DateTime('1 hour ago'), new \DateTime()],
            [new \DateTime('1 hour ago'), new \DateTime('+2 hour'), new \DateTime(), new \DateTime('+1 hour')],
            [new \DateTime('1 hour ago'), new \DateTime('+61 minutes'), new \DateTime(), new \DateTime('+1 hour')],
            [
                new \DateTime('1 hour ago'),
                new \DateTime('+61 minutes'),
                new \DateTime(),
                new \DateTime('+1 hour'),
                'test-key',
            ],
            [
                new \DateTime('2 hours ago'),
                new \DateTime('+5 hours'),
                new \DateTime('4 hours ago'),
                new \DateTime('+1 hour'),
            ],
        ];
    }

    /**
     * @dataProvider scheduleNextProvider
     */
    public function testScheduleNext(
        \DateTime $start,
        \DateTime $end,
        \DateTime $executionDate,
        \DateTime $scheduledExecutionDate = null,
        $key = null
    ) {
        $scheduler = $this->prophesize(Scheduler::class);
        if ($scheduledExecutionDate !== null) {
            $taskBuilder = $this->prophesize(TaskBuilder::class);
            $scheduler->createTask('test-task', 'test-task: workload')->willReturn($taskBuilder->reveal());

            $taskBuilder->cron('0 * * * *', $start, $end)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setKey($key)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setExecutionDate(
                Argument::that(
                    function (\DateTime $dateTime) use ($scheduledExecutionDate) {
                        $this->assertEquals(
                            $scheduledExecutionDate->setTime($scheduledExecutionDate->format('H'), 0, 0),
                            $dateTime,
                            '',
                            2
                        );

                        return true;
                    }
                )
            )->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->schedule()->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
        }

        $task = $this->prophesize(TaskInterface::class);
        $task->getTaskName()->willReturn('test-task');
        $task->getWorkload()->willReturn('test-task: workload');
        $task->getExecutionDate()->willReturn($executionDate);
        $task->getKey()->willReturn($key);

        $task = new HourlyTask($task->reveal(), $start, $end);

        $task->scheduleNext($scheduler->reveal());
    }
}

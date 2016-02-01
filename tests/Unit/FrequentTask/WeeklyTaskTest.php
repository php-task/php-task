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

use Cron\CronExpression;
use Prophecy\Argument;
use Task\FrequentTask\WeeklyTask;
use Task\Scheduler;
use Task\TaskBuilder;
use Task\TaskInterface;

/**
 * Test for class WeeklyTask.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class WeeklyTaskTest extends \PHPUnit_Framework_TestCase
{
    public function scheduleNextProvider()
    {
        return [
            [new \DateTime('2 weeks ago'), new \DateTime('1 week ago'), new \DateTime()],
            [
                new \DateTime('1 week ago'),
                new \DateTime('+2 weeks'),
                new \DateTime(),
                CronExpression::factory('@weekly')->getNextRunDate(),
            ],
            [
                new \DateTime('1 week ago'),
                new \DateTime('+8 day'),
                new \DateTime(),
                CronExpression::factory('@weekly')->getNextRunDate(),
            ],
            [
                new \DateTime('1 week ago'),
                new \DateTime('+8 days'),
                new \DateTime(),
                CronExpression::factory('@weekly')->getNextRunDate(),
                'test-key',
            ],
            [
                new \DateTime('2 weeks ago'),
                new \DateTime('+5 weeks'),
                new \DateTime('4 weeks ago'),
                CronExpression::factory('@weekly')->getNextRunDate(),
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

            $taskBuilder->cron('0 0 * * 0', $start, $end)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setKey($key)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setExecutionDate(
                Argument::that(
                    function (\DateTime $dateTime) use ($scheduledExecutionDate) {
                        $this->assertEquals($scheduledExecutionDate->setTime(0, 0, 0), $dateTime, '', 2);

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

        $task = new WeeklyTask($task->reveal(), $start, $end);

        $task->scheduleNext($scheduler->reveal());
    }
}

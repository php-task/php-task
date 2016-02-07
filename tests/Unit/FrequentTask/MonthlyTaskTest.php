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
use Task\FrequentTask\MonthlyTask;
use Task\Scheduler;
use Task\TaskBuilder;
use Task\TaskInterface;

/**
 * Test for class MonthlyTask.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class MonthlyTaskTest extends \PHPUnit_Framework_TestCase
{
    public function scheduleNextProvider()
    {
        return [
            [new \DateTime('2 months ago'), new \DateTime('1 month ago'), new \DateTime()],
            [new \DateTime('1 month ago'), new \DateTime('+2 months'), new \DateTime(), new \DateTime('+1 month')],
            [new \DateTime('1 month ago'), new \DateTime('+1 month +1 day'), new \DateTime(), new \DateTime('+1 month')],
            [
                new \DateTime('1 month ago'),
                new \DateTime('+1 month +1 day'),
                new \DateTime(),
                new \DateTime('+1 month'),
                'test-key',
            ],
            [
                new \DateTime('2 months ago'),
                new \DateTime('+5 months'),
                new \DateTime('4 months ago'),
                new \DateTime('+1 month'),
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

            $taskBuilder->cron('0 0 1 * *', $start, $end)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setKey($key)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setExecutionDate(
                Argument::that(
                    function (\DateTime $dateTime) use ($scheduledExecutionDate) {
                        $this->assertEquals(
                            $scheduledExecutionDate->setTime(0, 0, 0)
                                ->setDate(
                                    $scheduledExecutionDate->format('Y'),
                                    $scheduledExecutionDate->format('m'),
                                    1
                                ),
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

        $task = new MonthlyTask($task->reveal(), $start, $end);

        $task->scheduleNext($scheduler->reveal());
    }
}

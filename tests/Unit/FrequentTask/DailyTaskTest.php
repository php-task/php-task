<?php

namespace Unit\FrequentTask;

use Task\FrequentTask\DailyTask;
use Task\Scheduler;
use Task\TaskBuilder;
use Task\TaskInterface;

class DailyTaskTest extends \PHPUnit_Framework_TestCase
{
    public function scheduleNextProvider()
    {
        return [
            [new \DateTime('2 days ago'), new \DateTime('1 day ago')],
            [new \DateTime('1 day ago'), new \DateTime('+2 days'), new \DateTime('+1 day')],
            [new \DateTime('1 day ago'), new \DateTime('+1 days'), new \DateTime('+1 day')],
        ];
    }

    /**
     * @dataProvider scheduleNextProvider
     */
    public function testScheduleNext(\DateTime $start, \DateTime $end, $scheduledExecutionDate = null)
    {

        $scheduler = $this->prophesize(Scheduler::class);
        if ($scheduledExecutionDate !== null) {
            $taskBuilder = $this->prophesize(TaskBuilder::class);
            $scheduler->createTask('test-task', 'test-task: workload')->willReturn($taskBuilder->reveal());

            $taskBuilder->daily($start, $end)->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->setExecutionDate($scheduledExecutionDate)
                ->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
            $taskBuilder->schedule()->shouldBeCalledTimes(1)->willReturn($taskBuilder->reveal());
        }

        $task = $this->prophesize(TaskInterface::class);
        $task->getTaskName()->willReturn('test-task');
        $task->getWorkload()->willReturn('test-task: workload');

        $task = new DailyTask($task->reveal(), $start, $end);

        $task->scheduleNext($scheduler->reveal());
    }
}

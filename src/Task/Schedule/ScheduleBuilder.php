<?php

namespace Task\Schedule;

use Task\FactoryInterface;
use Task\TaskBuilderInterface;

/**
 * TODO introduce interface
 */
class ScheduleBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TaskBuilderInterface[]
     */
    private $taskBuilders = [];

    public function schedule($handlerClass, $workload = null)
    {
        return $this->taskBuilders[] = $this->factory->createTaskBuilder($handlerClass, $workload);
    }

    public function getSchedule()
    {
        return $this->factory->createSchedule(
            array_map(
                function (TaskBuilderInterface $taskBuilder) {
                    return $taskBuilder->getTask();
                },
                $this->taskBuilders
            )
        );
    }
}

<?php

namespace Task;

class TaskBuilderFactory implements TaskBuilderFactoryInterface
{
    public function create(SchedulerInterface $scheduler, $taskName, $workload)
    {
        return TaskBuilder::create($scheduler, $taskName, $workload);
    }
}

<?php

namespace Task;

interface TaskBuilderFactoryInterface
{
    public function create(SchedulerInterface $scheduler, $taskName, $workload);
}

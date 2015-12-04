<?php

namespace Task\FrequendTask;

use Task\SchedulerInterface;
use Task\TaskInterface;

interface FrequentTaskInterface extends TaskInterface
{
    public function scheduleNext(SchedulerInterface $scheduler);
}

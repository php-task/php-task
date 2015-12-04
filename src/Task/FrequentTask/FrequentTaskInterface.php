<?php

namespace Task\FrequentTask;

use Task\SchedulerInterface;
use Task\TaskInterface;

interface FrequentTaskInterface extends TaskInterface
{
    public function scheduleNext(SchedulerInterface $scheduler);
}

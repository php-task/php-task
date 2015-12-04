<?php

namespace Task\FrequendTask;

use Task\SchedulerInterface;

class DailyTask extends FrequentTask
{
    /**
     * {@inheritdoc}
     */
    public function scheduleNext(SchedulerInterface $scheduler)
    {
        $now = new \DateTime();
        $executionDate = $now->modify('+1 day');

        if ($executionDate >= $this->end) {
            return;
        }

        $scheduler->createTask($this->getTaskName(), $this->getWorkload())
            ->daily($this->start, $this->end)
            ->setExecutionDate($executionDate)
            ->schedule();
    }
}

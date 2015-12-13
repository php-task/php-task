<?php

namespace Task;

interface TaskBuilderInterface
{
    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return TaskBuilder
     */
    public function daily(\DateTime $start = null, \DateTime $end = null);

    /**
     * @param \DateTime $executionDate
     *
     * @return TaskBuilder
     */
    public function setExecutionDate(\DateTime $executionDate);

    /**
     * Schedules task with given scheduler.
     */
    public function schedule();
}

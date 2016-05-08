<?php

namespace Task\Schedule;

use Task\TaskInterface;

/**
 * TODO introduce interface
 */
class Schedule extends \ArrayIterator
{
    /**
     * @var TaskInterface[]
     */
    private $tasks = [];

    /**
     * @param TaskInterface[] $tasks
     */
    public function __construct(array $tasks)
    {
        parent::__construct($tasks);

        $this->tasks = $tasks;
    }

    /**
     * @return TaskInterface[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}

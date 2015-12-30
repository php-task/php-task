<?php

namespace Task\Event;

use Symfony\Component\EventDispatcher\Event;
use Task\TaskInterface;

class TaskEvent extends Event
{
    /**
     * @var TaskInterface
     */
    private $task;

    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }
}

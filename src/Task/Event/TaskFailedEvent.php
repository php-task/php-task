<?php

namespace Task\Event;

use Task\TaskInterface;

class TaskFailedEvent extends TaskEvent
{
    /**
     * @var \Exception
     */
    private $exception;

    public function __construct(TaskInterface $task, \Exception $exception)
    {
        parent::__construct($task);

        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}

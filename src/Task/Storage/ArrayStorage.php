<?php

namespace Task\Storage;

use Task\TaskInterface;

class ArrayStorage implements StorageInterface
{
    /**
     * @var TaskInterface[]
     */
    private $tasks = [];

    /**
     * {@inheritdoc}
     */
    public function store(TaskInterface $task)
    {
        $this->tasks[] = $task;
    }

    /**
     * {@inheritdoc}
     */
    public function findScheduled()
    {
        return new \ArrayIterator(
            array_filter(
                $this->tasks,
                function (TaskInterface $task) {
                    return !$task->isCompleted() && $task->getExecutionDate() <= new \DateTime();
                }
            )
        );
    }
}

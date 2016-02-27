<?php

namespace Task\Storage;

use Task\TaskInterface;

interface TaskRepositoryInterface
{
    /**
     * @param TaskInterface $task
     */
    public function add(TaskInterface $task);

    /**
     * @return TaskInterface[]
     */
    public function findAll();

    /**
     * @return TaskInterface[]
     */
    public function findEndBeforeNow();
}

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
     * @param int|null $limit
     *
     * @return TaskInterface[]
     */
    public function findAll($limit = null);

    /**
     * @return TaskInterface[]
     */
    public function findEndBeforeNow();
}

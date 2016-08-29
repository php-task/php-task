<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Storage;

use Task\TaskInterface;

/**
 * Interface for task repository.
 */
interface TaskRepositoryInterface
{
    /**
     * Store task.
     *
     * @param TaskInterface $task
     */
    public function store(TaskInterface $task);

    /**
     * Returns all tasks.
     *
     * @param int|null $limit
     *
     * @return TaskInterface[]
     */
    public function findAll($limit = null);

    /**
     * Used to find tasks which has end-date before now.
     *
     * @return TaskInterface[]
     */
    public function findEndBeforeNow();

    /**
     * Clear storage.
     */
    public function clear();
}

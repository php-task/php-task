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
     * Find task for given uuid.
     *
     * @param string $uuid
     *
     * @return TaskInterface
     */
    public function findByUuid($uuid);

    /**
     * Create task.
     *
     * @param string $handlerClass
     * @param string|\Serializable $workload
     *
     * @return TaskInterface
     */
    public function create($handlerClass, $workload = null);

    /**
     * Save task.
     *
     * @param TaskInterface $task
     */
    public function save(TaskInterface $task);

    /**
     * Remove task.
     *
     * @param TaskInterface $task
     */
    public function remove(TaskInterface $task);

    /**
     * Returns all tasks.
     *
     * @param int $page
     * @param int $pageSize
     *
     * @return TaskInterface[]
     */
    public function findAll($page = 1, $pageSize = null);

    /**
     * Used to find tasks which has end-date before now.
     *
     * @return TaskInterface[]
     */
    public function findEndBeforeNow();
}

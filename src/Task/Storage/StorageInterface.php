<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Storage;

use Task\TaskInterface;

/**
 * Interface for task storage.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface StorageInterface
{
    /**
     * Stores task in storage.
     *
     * @param TaskInterface $task
     */
    public function store(TaskInterface $task);

    /**
     * Returns scheduled tasks.
     *
     * Depends on completed flag and start|end date.
     *
     * @return TaskInterface[]
     */
    public function findScheduled();

    /**
     * Returns all tasks.
     *
     * @return TaskInterface[]
     */
    public function findAll();

    /**
     * Update task in storage.
     *
     * @param TaskInterface $task
     */
    public function persist(TaskInterface $task);

    /**
     * Clears storage and removes all tasks.
     */
    public function clear();
}

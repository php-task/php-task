<?php

namespace Task\Storage;

use Task\TaskInterface;

interface StorageInterface
{
    /**
     * @param TaskInterface $task
     */
    public function store(TaskInterface $task);

    /**
     * @return TaskInterface[]
     */
    public function findScheduled();

    /**
     * @return TaskInterface[]
     */
    public function findAll();

    /**
     * @param TaskInterface $task
     */
    public function persist(TaskInterface $task);

    public function clear();
}

<?php

namespace Task\Storage;

use Task\TaskInterface;

interface StorageInterface
{
    public function store(TaskInterface $task);

    public function findScheduled();
}

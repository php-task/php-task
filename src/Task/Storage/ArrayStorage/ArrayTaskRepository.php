<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Storage\ArrayStorage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Storage\TaskRepositoryInterface;
use Task\TaskInterface;

/**
 * Storage task in an array.
 */
class ArrayTaskRepository implements TaskRepositoryInterface
{
    /**
     * @var Collection
     */
    private $taskCollection;

    /**
     * @param TaskInterface[] $tasks
     */
    public function __construct(array $tasks = [])
    {
        $this->taskCollection = new ArrayCollection($tasks);
    }

    /**
     * {@inheritdoc}
     */
    public function store(TaskInterface $task)
    {
        $this->taskCollection->add($task);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll($limit = null)
    {
        return $this->taskCollection->slice(0, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function findEndBeforeNow()
    {
        $now = new \DateTime();

        return $this->taskCollection->filter(
            function (TaskInterface $task) use ($now) {
                return $task->getLastExecution() === null || $task->getLastExecution() > $now;
            }
        );
    }
}

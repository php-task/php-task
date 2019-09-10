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
use Task\Task;
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
     * @param Collection $tasks
     */
    public function __construct(Collection $tasks = null)
    {
        $this->taskCollection = $tasks ?: new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function findByUuid($uuid)
    {
        /** @var TaskInterface $task */
        foreach ($this->taskCollection as $task) {
            if ($task->getUuid() === $uuid) {
                return $task;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function create($handlerClass, $workload = null)
    {
        return new Task($handlerClass, $workload);
    }

    /**
     * {@inheritdoc}
     */
    public function save(TaskInterface $task)
    {
        if ($this->taskCollection->contains($task)) {
            return $this;
        }

        $this->taskCollection->add($task);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(TaskInterface $task)
    {
        $this->taskCollection->removeElement($task);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll($page = 1, $pageSize = null)
    {
        return array_values($this->taskCollection->slice(($page - 1) * $pageSize, $pageSize));
    }

    /**
     * {@inheritdoc}
     */
    public function findEndBeforeNow()
    {
        $now = new \DateTime();

        return array_values(
            $this->taskCollection->filter(
                function (TaskInterface $task) use ($now) {
                    return null === $task->getLastExecution() || $task->getLastExecution() > $now;
                }
            )->toArray()
        );
    }
}

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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\TaskInterface;

/**
 * Holds tasks in in-memory array.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class ArrayStorage implements StorageInterface
{
    /**
     * @var Collection
     */
    private $tasks;

    public function __construct(Collection $tasks = null)
    {
        if ($tasks === null) {
            $tasks = new ArrayCollection();
        }

        $this->tasks = $tasks;
    }

    /**
     * {@inheritdoc}
     */
    public function store(TaskInterface $task)
    {
        if ($task->getKey()) {
            $key = $task->getKey();

            $tasks = $this->tasks->filter(
                function (TaskInterface $task) use ($key) {
                    return !$task->isCompleted() && $task->getKey() === $key;
                }
            );

            if ($tasks->count() > 0) {
                // TODO update task (warning execution date should not be changed)

                return;
            }
        }

        $this->tasks->add($task);
    }

    /**
     * {@inheritdoc}
     */
    public function findScheduled()
    {
        return $this->tasks->filter(
            function (TaskInterface $task) {
                return !$task->isCompleted() && $task->getExecutionDate() <= new \DateTime();
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findAll()
    {
        return $this->tasks;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(TaskInterface $task)
    {
        if (!$this->tasks->contains($task)) {
            throw new TaskNotExistsException($task);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->tasks->clear();
    }
}

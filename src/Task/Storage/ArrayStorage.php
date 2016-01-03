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
 * @author Johannes Wachter <@wachterjohannes>
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

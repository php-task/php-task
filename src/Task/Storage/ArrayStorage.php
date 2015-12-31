<?php

namespace Task\Storage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\TaskInterface;

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
    }

    public function clear()
    {
        $this->tasks->clear();
    }
}

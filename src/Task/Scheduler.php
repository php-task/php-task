<?php

namespace Task;

use Task\FrequendTask\FrequentTaskInterface;
use Task\Handler\RegistryInterface;
use Task\Storage\StorageInterface;

class Scheduler implements SchedulerInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(StorageInterface $storage, RegistryInterface $registry)
    {
        $this->storage = $storage;
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function createTask($taskName, $workload)
    {
        return TaskBuilder::create($this, $taskName, $workload);
    }

    /**
     * {@inheritdoc}
     */
    public function schedule(TaskInterface $task)
    {
        $this->storage->store($task);
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        /** @var TaskInterface $task */
        foreach ($this->storage->findScheduled() as $task) {
            $result = $this->registry->run($task->getTaskName(), $task->getWorkload());

            $task->setResult($result);
            $task->setCompleted();

            // TODO move to event-dispatcher
            if ($task instanceof FrequentTaskInterface) {
                $task->scheduleNext($this);
            }
        }
    }
}

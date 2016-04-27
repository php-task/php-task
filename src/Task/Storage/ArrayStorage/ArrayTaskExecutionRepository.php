<?php

namespace Task\Storage\ArrayStorage;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Task\Execution\TaskExecutionInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

class ArrayTaskExecutionRepository implements TaskExecutionRepositoryInterface
{
    /**
     * @var Collection
     */
    private $taskExecutionCollection;

    public function __construct(array $taskExecutions = [])
    {
        $this->taskExecutionCollection = new ArrayCollection($taskExecutions);
    }

    /**
     * {@inheritdoc}
     */
    public function findScheduled()
    {
        $now = new \DateTime();

        return $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($now) {
                return $execution->getStatus() === TaskStatus::PLANNED && $execution->getScheduleTime() < $now;
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findByStartTime(TaskInterface $task, \DateTime $scheduleTime)
    {
        $filtered = $this->taskExecutionCollection->filter(
            function (TaskExecutionInterface $execution) use ($task, $scheduleTime) {
                return $execution->getTask()->getUuid() === $task->getUuid()
                    && $execution->getScheduleTime() === $scheduleTime;
            }
        );

        if ($filtered->count() === 0) {
            return;
        }

        return $filtered->first();
    }

    public function findAll($limit = null)
    {
        return $this->taskExecutionCollection->slice(0, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function save(TaskExecutionInterface $execution)
    {
        $this->taskExecutionCollection->add($execution);
    }

    /**
     * {@inheritdoc}
     */
    public function add(TaskExecutionInterface $execution)
    {
        // do nothing
    }
}

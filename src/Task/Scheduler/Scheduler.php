<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Scheduler;

use Task\FactoryInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\Storage\TaskRepositoryInterface;
use Task\TaskInterface;
use Task\TaskStatus;

class Scheduler implements SchedulerInterface
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $executionRepository;

    public function __construct(
        FactoryInterface $factory,
        TaskRepositoryInterface $taskRepository,
        TaskExecutionRepositoryInterface $executionRepository
    ) {
        $this->factory = $factory;
        $this->taskRepository = $taskRepository;
        $this->executionRepository = $executionRepository;
    }

    public function createTask($handlerClass, $workload = null)
    {
        return $this->factory->createTaskBuilder($this, $handlerClass, $workload);
    }

    public function addTask(TaskInterface $task)
    {
        $this->taskRepository->add($task);

        $this->scheduleTasks();
    }

    /**
     * {@inheritdoc}
     */
    public function scheduleTasks()
    {
        $tasks = $this->taskRepository->findEndBeforeNow();

        foreach ($tasks as $task) {
            $scheduleTime = $task->getInterval() ?
                $task->getInterval()->getNextRunDate() : $task->getFirstExecution();

            if (null === $this->executionRepository->findByStartTime($task, $scheduleTime)) {
                $execution = $this->factory->createTaskExecution($task, $scheduleTime);
                $execution->setStatus(TaskStatus::PLANNED);

                $this->executionRepository->add($execution);
            }
        }
    }
}

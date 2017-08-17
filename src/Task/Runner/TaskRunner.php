<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Runner;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Task\Event\Events;
use Task\Event\TaskExecutionEvent;
use Task\Execution\TaskExecutionInterface;
use Task\Executor\ExecutorInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\TaskStatus;

/**
 * Executes scheduled tasks.
 */
class TaskRunner implements TaskRunnerInterface
{
    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $taskExecutionRepository;

    /**
     * @var ExecutionFinderInterface
     */
    private $executionFinder;

    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param TaskExecutionRepositoryInterface $taskExecutionRepository
     * @param ExecutionFinderInterface $executionFinder
     * @param ExecutorInterface $executor
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TaskExecutionRepositoryInterface $taskExecutionRepository,
        ExecutionFinderInterface $executionFinder,
        ExecutorInterface $executor,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->taskExecutionRepository = $taskExecutionRepository;
        $this->executionFinder = $executionFinder;
        $this->executor = $executor;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function runTasks()
    {
        foreach ($this->executionFinder->find() as $execution) {
            $this->run($execution);
        }
    }

    /**
     * Run execution with given handler.
     *
     * @param TaskExecutionInterface $execution
     */
    private function run(TaskExecutionInterface $execution)
    {
        $start = microtime(true);
        $execution->setStartTime(new \DateTime());
        $execution->setStatus(TaskStatus::RUNNING);
        $this->taskExecutionRepository->save($execution);

        try {
            $execution = $this->hasPassed($execution, $this->handle($execution));
        } catch (\Exception $exception) {
            $execution = $this->hasFailed($execution, $exception);
        } finally {
            $this->finalize($execution, $start);
        }
    }

    /**
     * Handle given execution and fire before and after events.
     *
     * @param TaskExecutionInterface $execution
     *
     * @return \Serializable|string
     *
     * @throws \Exception
     */
    private function handle(TaskExecutionInterface $execution)
    {
        $this->eventDispatcher->dispatch(
            Events::TASK_BEFORE,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );

        try {
            return $this->executor->execute($execution);
        } finally {
            $this->eventDispatcher->dispatch(
                Events::TASK_AFTER,
                new TaskExecutionEvent($execution->getTask(), $execution)
            );
        }
    }

    /**
     * The given task passed the run.
     *
     * @param TaskExecutionInterface $execution
     * @param mixed $result
     *
     * @return TaskExecutionInterface
     */
    private function hasPassed(TaskExecutionInterface $execution, $result)
    {
        // this find is necessary if the storage because the storage could be
        // invalid (clear in doctrine) after handling an execution.
        $execution = $this->taskExecutionRepository->findByUuid($execution->getUuid());
        $execution->setStatus(TaskStatus::COMPLETED);
        $execution->setResult($result);

        $this->eventDispatcher->dispatch(
            Events::TASK_PASSED,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );

        return $execution;
    }

    /**
     * The given task failed the run.
     *
     * @param TaskExecutionInterface $execution
     * @param \Exception $exception
     *
     * @return TaskExecutionInterface
     */
    private function hasFailed(TaskExecutionInterface $execution, \Exception $exception)
    {
        // this find is necessary if the storage because the storage could be
        // invalid (clear in doctrine) after handling an execution.
        $execution = $this->taskExecutionRepository->findByUuid($execution->getUuid());
        $execution->setException($exception->__toString());
        $execution->setStatus(TaskStatus::FAILED);

        $this->eventDispatcher->dispatch(
            Events::TASK_FAILED,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );

        return $execution;
    }

    /**
     * Finalizes given execution.
     *
     * @param TaskExecutionInterface $execution
     * @param int $start
     */
    private function finalize(TaskExecutionInterface $execution, $start)
    {
        $execution->setEndTime(new \DateTime());
        $execution->setDuration(microtime(true) - $start);

        $this->eventDispatcher->dispatch(
            Events::TASK_FINISHED,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );

        $this->taskExecutionRepository->save($execution);
    }
}

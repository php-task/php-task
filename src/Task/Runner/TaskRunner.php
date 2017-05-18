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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Task\Event\Events;
use Task\Event\TaskExecutionEvent;
use Task\Execution\TaskExecutionInterface;
use Task\Handler\TaskHandlerFactoryInterface;
use Task\Handler\TaskHandlerInterface;
use Task\Lock\LockingTaskHandlerInterface;
use Task\Lock\LockInterface;
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
     * @var TaskHandlerFactoryInterface
     */
    private $taskHandlerFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LockInterface
     */
    private $lock;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TaskExecutionRepositoryInterface $executionRepository
     * @param TaskHandlerFactoryInterface $taskHandlerFactory
     * @param LockInterface $lock
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface $logger
     */
    public function __construct(
        TaskExecutionRepositoryInterface $executionRepository,
        TaskHandlerFactoryInterface $taskHandlerFactory,
        LockInterface $lock,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger = null
    ) {
        $this->taskExecutionRepository = $executionRepository;
        $this->taskHandlerFactory = $taskHandlerFactory;
        $this->lock = $lock;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function runTasks()
    {
        $runTime = new \DateTime();

        while ($execution = $this->taskExecutionRepository->findNextScheduled($runTime)) {
            $handler = $this->taskHandlerFactory->create($execution->getHandlerClass());
            if ($handler instanceof LockingTaskHandlerInterface) {
                $this->runWithLock($handler, $execution);

                continue;
            }

            $this->run($handler, $execution);
        }
    }

    /**
     * Run execution with given handler.
     *
     * @param TaskHandlerInterface $handler
     * @param TaskExecutionInterface $execution
     */
    public function run(TaskHandlerInterface $handler, TaskExecutionInterface $execution)
    {
        $start = microtime(true);
        $execution->setStartTime(new \DateTime());
        $execution->setStatus(TaskStatus::RUNNING);
        $this->taskExecutionRepository->save($execution);

        try {
            $execution = $this->hasPassed($execution, $this->handle($handler, $execution));
        } catch (\Exception $exception) {
            $execution = $this->hasFailed($execution, $exception);
        } finally {
            $this->finalize($execution, $start);
        }
    }

    /**
     * Run execution with given handler and use locking component.
     *
     * @param LockingTaskHandlerInterface $handler
     * @param TaskExecutionInterface $execution
     */
    public function runWithLock(LockingTaskHandlerInterface $handler, TaskExecutionInterface $execution)
    {
        $key = $handler->getLockKey($execution->getWorkload());
        if ($this->lock->isAcquired($key) || !$this->lock->acquire($key)) {
            $this->logger->warning(sprintf('Execution "%s" is locked and skipped.', $execution->getUuid()));

            return;
        }

        $this->run($handler, $execution);

        $this->lock->release($key);
    }

    /**
     * Handle given execution and fire before and after events.
     *
     * @param TaskHandlerInterface $handler
     * @param TaskExecutionInterface $execution
     *
     * @return \Serializable|string
     *
     * @throws \Exception
     */
    private function handle(TaskHandlerInterface $handler, TaskExecutionInterface $execution)
    {
        $this->eventDispatcher->dispatch(
            Events::TASK_BEFORE,
            new TaskExecutionEvent($execution->getTask(), $execution)
        );

        try {
            return $handler->handle($execution->getWorkload());
        } catch (\Exception $exception) {
            throw $exception;
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

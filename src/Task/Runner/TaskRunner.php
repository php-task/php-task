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
use Task\Handler\TaskHandlerFactoryInterface;
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
     * @param TaskExecutionRepositoryInterface $executionRepository
     * @param TaskHandlerFactoryInterface $taskHandlerFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TaskExecutionRepositoryInterface $executionRepository,
        TaskHandlerFactoryInterface $taskHandlerFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->taskExecutionRepository = $executionRepository;
        $this->taskHandlerFactory = $taskHandlerFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function runTasks()
    {
        $executions = $this->taskExecutionRepository->findScheduled();

        foreach ($executions as $execution) {
            // this find is neccesary if the storage because the storage could be
            // invalid (clear in doctrine) after handling an execution.
            $execution = $this->taskExecutionRepository->findByUuid($execution->getUuid());

            $start = microtime(true);
            $execution->setStartTime(new \DateTime());
            $execution->setStatus(TaskStatus::RUNNING);
            $this->taskExecutionRepository->save($execution);

            try {
                $result = $this->handle($execution);

                // this find is neccesary if the storage because the storage could be
                // invalid (clear in doctrine) after handling an execution.
                $execution = $this->taskExecutionRepository->findByUuid($execution->getUuid());
                $execution->setStatus(TaskStatus::COMPLETED);
                $execution->setResult($result);

                $this->eventDispatcher->dispatch(
                    Events::TASK_PASSED,
                    new TaskExecutionEvent($execution->getTask(), $execution)
                );
            } catch (\Exception $ex) {
                // this find is neccesary if the storage because the storage could be
                // invalid (clear in doctrine) after handling an execution.
                $execution = $this->taskExecutionRepository->findByUuid($execution->getUuid());
                $execution->setException($ex->__toString());
                $execution->setStatus(TaskStatus::FAILED);

                $this->eventDispatcher->dispatch(
                    Events::TASK_FAILED,
                    new TaskExecutionEvent($execution->getTask(), $execution)
                );
            }

            $execution->setEndTime(new \DateTime());
            $execution->setDuration(microtime(true) - $start);

            $this->eventDispatcher->dispatch(
                Events::TASK_FINISHED,
                new TaskExecutionEvent($execution->getTask(), $execution)
            );
            $this->taskExecutionRepository->save($execution);
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
        $handler = $this->taskHandlerFactory->create($execution->getHandlerClass());

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
}

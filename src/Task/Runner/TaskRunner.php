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
            $handler = $this->taskHandlerFactory->create($execution->getHandlerClass());

            $start = microtime(true);
            $execution->setStartTime(new \DateTime());

            try {
                $this->eventDispatcher->dispatch(
                    Events::TASK_BEFORE,
                    new TaskExecutionEvent($execution->getTask(), $execution)
                );

                $result = $handler->handle($execution->getWorkload());

                $execution->setStatus(TaskStatus::COMPLETE);
                $execution->setResult($result);

                $this->eventDispatcher->dispatch(
                    Events::TASK_PASSED,
                    new TaskExecutionEvent($execution->getTask(), $execution)
                );
            } catch (\Exception $ex) {
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
        }

        $this->taskExecutionRepository->flush();
    }
}

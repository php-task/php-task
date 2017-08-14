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
use Task\Handler\TaskHandlerFactoryInterface;
use Task\Lock\LockingTaskHandlerInterface;
use Task\Lock\LockInterface;
use Task\Storage\TaskExecutionRepositoryInterface;

/**
 * Find pending executions.
 */
class PendingExecutionFinder implements ExecutionFinderInterface
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
     * @var LockInterface
     */
    private $lock;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TaskExecutionRepositoryInterface $taskExecutionRepository
     * @param TaskHandlerFactoryInterface $taskHandlerFactory
     * @param LockInterface $lock
     * @param LoggerInterface $logger
     */
    public function __construct(
        TaskExecutionRepositoryInterface $taskExecutionRepository,
        TaskHandlerFactoryInterface $taskHandlerFactory,
        LockInterface $lock,
        LoggerInterface $logger = null
    ) {
        $this->taskExecutionRepository = $taskExecutionRepository;
        $this->taskHandlerFactory = $taskHandlerFactory;
        $this->lock = $lock;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function find()
    {
        $runTime = new \DateTime();

        $skippedExecutions = [];
        while ($execution = $this->taskExecutionRepository->findNextScheduled($runTime, $skippedExecutions)) {
            $handler = $this->taskHandlerFactory->create($execution->getHandlerClass());
            if (!$handler instanceof LockingTaskHandlerInterface) {
                yield $execution;

                continue;
            }

            $key = $handler->getLockKey($execution->getWorkload());
            if ($this->lock->isAcquired($key) || !$this->lock->acquire($key)) {
                $skippedExecutions[] = $execution->getUuid();
                $this->logger->warning(sprintf('Execution "%s" is locked and skipped.', $execution->getUuid()));

                continue;
            }

            yield $execution;

            $this->lock->release($key);
        }
    }
}

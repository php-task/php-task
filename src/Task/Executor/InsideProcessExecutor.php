<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Executor;

use Task\Execution\TaskExecutionInterface;
use Task\Handler\TaskHandlerFactoryInterface;

/**
 * Executes handler inside current process.
 */
class InsideProcessExecutor implements ExecutorInterface
{
    /**
     * @var TaskHandlerFactoryInterface
     */
    private $handlerFactory;

    /**
     * @param TaskHandlerFactoryInterface $handlerFactory
     */
    public function __construct(TaskHandlerFactoryInterface $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(TaskExecutionInterface $execution)
    {
        $handler = $this->handlerFactory->create($execution->getHandlerClass());

        try {
            return $handler->handle($execution->getWorkload());
        } catch (FailedException $exception) {
            throw $exception->getPrevious();
        } catch (\Exception $exception) {
            if (!$handler instanceof RetryTaskHandlerInterface) {
                throw $exception;
            }

            throw new RetryException($handler->getMaximumAttempts(), $exception);
        }
    }
}

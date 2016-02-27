<?php

namespace Task\Runner;

use Task\Handler\TaskHandlerFactoryInterface;
use Task\Storage\TaskExecutionRepositoryInterface;
use Task\TaskStatus;

class TaskRunner implements TaskRunnerInterface
{
    /**
     * @var TaskExecutionRepositoryInterface
     */
    private $executionRepository;

    /**
     * @var TaskHandlerFactoryInterface
     */
    private $handlerRegistry;

    public function runTasks()
    {
        $executions = $this->executionRepository->findScheduled();

        foreach ($executions as $execution) {
            $handler = $this->handlerRegistry->create($execution->getHandlerClass());

            try {
                $result = $handler->handle($execution->getWorkload());

                $execution->setStatus(TaskStatus::COMPLETE);
                $execution->setResult($result);
            } catch (\Exception $ex) {
                $execution->setException($ex);
                $execution->setStatus(TaskStatus::FAILED);
            }
        }
    }
}

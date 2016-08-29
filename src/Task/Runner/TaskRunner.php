<?php

namespace Task\Runner;

use Task\Execution\TaskExecutionRepositoryInterface;
use Task\Handler\TaskHandlerFactoryInterface;
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
    private $taskHandlerFactory;

    public function __construct(
        TaskExecutionRepositoryInterface $executionRepository,
        TaskHandlerFactoryInterface $taskHandlerFactory
    ) {
        $this->executionRepository = $executionRepository;
        $this->taskHandlerFactory = $taskHandlerFactory;
    }

    public function runTasks()
    {
        $executions = $this->executionRepository->findScheduled();

        foreach ($executions as $execution) {
            $handler = $this->taskHandlerFactory->create($execution->getHandlerClass());

            $start = microtime(true);
            $execution->setStartTime(new \DateTime());

            try {
                $result = $handler->handle($execution->getWorkload());

                $execution->setEndTime(new \DateTime());
                $execution->setDuration(microtime(true) - $start);

                $execution->setStatus(TaskStatus::COMPLETE);
                $execution->setResult($result);
            } catch (\Exception $ex) {
                $execution->setException($ex->__toString());
                $execution->setStatus(TaskStatus::FAILED);

                $execution->setEndTime(new \DateTime());
                $execution->setDuration(microtime(true) - $start);
            }

            $this->executionRepository->save($execution);
        }
    }
}

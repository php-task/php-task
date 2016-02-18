<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task;

use Cron\CronExpression;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Task\Event\Events;
use Task\Event\TaskEvent;
use Task\Event\TaskFailedEvent;
use Task\FrequentTask\FrequentTaskInterface;
use Task\Handler\RegistryInterface;
use Task\Storage\StorageInterface;

/**
 * Central component which handles tasks.
 *
 * Allows to create and run tasks.
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
class Scheduler implements SchedulerInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @var TaskBuilderFactoryInterface
     */
    private $factory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        StorageInterface $storage,
        RegistryInterface $registry,
        TaskBuilderFactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger = null
    ) {
        $this->storage = $storage;
        $this->registry = $registry;
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function createTask($handlerName, $workload = null)
    {
        return $this->factory->create($this, $handlerName, $workload);
    }

    /**
     * {@inheritdoc}
     */
    public function createTaskAndSchedule($handlerName, $workload = null, $interval = null, $key = null)
    {
        $taskBuilder = $this->createTask($handlerName, $workload);

        if ($interval) {
            if (CronExpression::isValidExpression($interval)) {
                $taskBuilder->cron($interval);
            } else {
                $taskBuilder->{$interval}();
            }
        }

        if ($key) {
            $taskBuilder->setKey($key);
        }

        return $taskBuilder->schedule();
    }

    /**
     * {@inheritdoc}
     */
    public function schedule(TaskInterface $task)
    {
        if (!$this->registry->has($task->getTaskName())) {
            throw new \Exception($this->getNoHandlerFoundMessage($task));
        }

        $this->storage->store($task);

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        /** @var TaskInterface $task */
        foreach ($this->storage->findScheduled() as $task) {
            if (!$this->registry->has($task->getTaskName())) {
                $this->warning($this->getNoHandlerFoundMessage($task));
                continue;
            }

            $this->eventDispatcher->dispatch(Events::TASK_BEFORE, new TaskEvent($task));

            try {
                $startedAt = microtime();
                $result = $this->registry->run($task->getTaskName(), $task->getWorkload());
                $finishedAt = microtime();

                $task->setResult($result);
                $task->setCompleted();
                $task->addExecution(new TaskExecution($startedAt, $finishedAt));

                $this->storage->persist($task);

                $this->eventDispatcher->dispatch(Events::TASK_PASSED, new TaskEvent($task));
            } catch (\Exception $ex) {
                // TODO set task failed. failed tasks, can be restarted by adding a flag to run command.

                $this->eventDispatcher->dispatch(Events::TASK_FAILED, new TaskFailedEvent($task, $ex));

                throw $ex;
            }

            $this->eventDispatcher->dispatch(Events::TASK_AFTER, new TaskEvent($task));

            // TODO move to event-dispatcher
            if ($task instanceof FrequentTaskInterface) {
                $task->scheduleNext($this);
            }
        }
    }

    /**
     * Returns message for "no handler found".
     *
     * @param TaskInterface $task
     *
     * @return string
     */
    private function getNoHandlerFoundMessage(TaskInterface $task)
    {
        return sprintf('No handler found handler for "%s" task.', $task->getTaskName());
    }

    /**
     * Write a warning into log.
     *
     * @param string $message
     */
    private function warning($message)
    {
        if (null !== $this->logger) {
            return;
        }

        $this->logger->warning($message);
    }
}

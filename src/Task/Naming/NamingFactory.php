<?php

namespace Tasks\Naming;

use Tasks\Scheduler\TaskInterface;
use Tasks\TaskRunner\WorkerInterface;

/**
 * Default implementation of naming factory.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class NamingFactory implements NamingFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function fromWorker(WorkerInterface $worker)
    {
        return sprintf('%s.%s', $worker->getNamespace(), $worker->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function fromTask(TaskInterface $task)
    {
        return $task->getWorkerName();
    }
}

<?php


namespace Task\Handler;

use Task\Scheduler\TaskInterface;

/**
 * Implements middleware which run command with a handler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class HandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var HandlerInterface[]
     */
    protected $handler = [];

    public function addHandler($name, HandlerInterface $handler)
    {
        $this->handler[$name] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($handlerName, TaskInterface $task, callable $next)
    {
        $result = $this->handler[$handlerName]->handle($task);

        $task->setCompleted();
        $task->setResult($result);

        $next();
    }
}

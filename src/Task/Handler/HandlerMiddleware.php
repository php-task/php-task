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
     * @var HandlerInterface
     */
    protected $handler;

    public function __construct(HandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(TaskInterface $task, MiddlewareInterface $next)
    {
        $this->handler->handle($task);
    }
}

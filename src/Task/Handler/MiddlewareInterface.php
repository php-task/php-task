<?php

namespace Task\Handler;

use Task\Scheduler\TaskInterface;

/**
 * Defines interface for a task-middleware.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface MiddlewareInterface
{
    public function handle($handlerName, TaskInterface $task, callable $next);
}

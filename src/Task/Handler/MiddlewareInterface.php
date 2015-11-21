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
    public function handle(TaskInterface $task, MiddlewareInterface $next);
}

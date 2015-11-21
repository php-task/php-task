<?php

namespace Task\Handler;

use Task\Scheduler\TaskInterface;

/**
 * Defines interface for a task-handler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface HandlerInterface
{
    public function handle(TaskInterface $task);
}

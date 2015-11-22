<?php

namespace Task\Registry;

use Task\Scheduler\TaskInterface;

/**
 * Defines interface for a handler-registry.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface HandlerRegistryInterface
{
    /**
     * Handle given Tasks.
     *
     * @param string $handlerName
     * @param TaskInterface $task
     */
    public function handle($handlerName, TaskInterface $task);
}

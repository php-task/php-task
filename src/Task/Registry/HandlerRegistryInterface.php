<?php

namespace Task\Registry;

/**
 * Defines interface for a handler-registry.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface HandlerRegistryInterface
{
    /**
     * Handle scheduled Tasks.
     */
    public function handle();
}

<?php

namespace Task\Registry;

use Task\Handler\HandlerInterface;
use Task\Handler\MiddlewareInterface;

/**
 * Registry for task-handler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
abstract class HandlerRegistry implements HandlerRegistryInterface
{
    public function addMiddleware(MiddlewareInterface $middleware)
    {

    }

    public function addHandler(HandlerInterface $handler)
    {
    }
}

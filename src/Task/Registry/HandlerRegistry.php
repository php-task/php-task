<?php

namespace Task\Registry;

use Task\Handler\MiddlewareInterface;
use Task\Scheduler\TaskInterface;

/**
 * Registry for task-handler.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
abstract class HandlerRegistry implements HandlerRegistryInterface
{
    /**
     * @var MiddlewareInterface[][]
     */
    private $middleware;

    /**
     * @var callable
     */
    private $callable;

    public function addMiddleware(MiddlewareInterface $middleware, $priority)
    {
        $this->middleware[$priority][] = $middleware;

        $this->callable = null;
    }

    public function handle($handlerName, TaskInterface $task)
    {
        if (!$this->callable) {
            $this->callable = $this->buildChain();
        }

        $this->callable($handlerName, $task);
    }

    private function buildChain()
    {
        ksort($this->middleware);
        call_user_func_array('array_merge', $this->middleware);


        $lastCallable = function () {
            // the final callable is a no-op
        };

        /** @var MiddlewareInterface $middleware */
        while ($middleware = array_pop($this->middleware)) {
            $lastCallable = function ($handlerName, $task) use ($middleware, $lastCallable) {
                return $middleware->handle($handlerName, $task, $lastCallable);
            };
        }

        return $lastCallable;
    }
}

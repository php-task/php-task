<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Handler;

/**
 * Task handler registry.
 *
 * Allows to add handler instances to run tasks.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class Registry implements RegistryInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handler = [];

    /**
     * Adds handler with given name to registry.
     *
     * @param string $name handler name.
     * @param HandlerInterface $handler
     */
    public function add($name, HandlerInterface $handler)
    {
        $this->handler[$name] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function run($name, $workload)
    {
        if (!$this->has($name)) {
            throw new HandlerNotExistsException($name);
        }

        return $this->handler[$name]->handle($workload);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return array_key_exists($name, $this->handler);
    }
}

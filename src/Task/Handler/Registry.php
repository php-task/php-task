<?php

namespace Task\Handler;

use Task\TaskInterface;

class Registry implements RegistryInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handler = [];

    public function add($name, HandlerInterface $handler)
    {
        $this->handler[$name] = $handler;
    }

    /**
     * {@inheritdoc}
     */
    public function run($name, $workload)
    {
        $this->handler[$name]($workload);
    }
}

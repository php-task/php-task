<?php

namespace Task\Handler;

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

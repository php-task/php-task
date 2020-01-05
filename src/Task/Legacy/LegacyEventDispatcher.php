<?php

namespace Task\Legacy;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;

class LegacyEventDispatcher
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var bool
     */
    private $legacy;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        $reflection = new \ReflectionMethod(EventDispatcherInterface::class, 'dispatch');
        $this->legacy = $reflection->getParameters()[0]->getType()->getName() === 'string';
    }

    public function dispatch(string $eventName, $event)
    {
        if (!$this->legacy) {
            return $this->eventDispatcher->dispatch($event, $eventName);
        }

        return $this->eventDispatcher->dispatch($eventName, $event);
    }
}

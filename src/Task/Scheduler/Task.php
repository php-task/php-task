<?php

namespace Task\Scheduler;

/**
 * Default task implementation.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class Task implements TaskInterface
{
    /**
     * @var string
     */
    private $workload;

    public function __construct($workload)
    {
        $this->workload = $workload;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkload()
    {
        return $this->workload;
    }
}

<?php

namespace Tasks\TaskRunner;

use Tasks\Scheduler\TaskInterface;

interface WorkerInterface
{
    public function run(TaskInterface $task);

    public function getNamespace();

    public function getName();
}

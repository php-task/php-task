<?php

namespace Task\Handler;

use Task\TaskInterface;

interface HandlerInterface
{
    public function handle(TaskInterface $task);
}

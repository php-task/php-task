<?php

namespace Task;

/**
 * Container class for task status constants.
 */
final class TaskStatus
{
    private function __construct()
    {
    }

    const PLANNED = 'planned';
    const STARTED = 'started';
    const COMPLETE = 'completed';
    const FAILED = 'failed';
}

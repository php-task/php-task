<?php

namespace Task;

/**
 * Container class for task status constants.
 */
final class TaskStatus
{
    const PLANNED = 'planned';
    const STARTED = 'started';
    const COMPLETE = 'completed';
    const FAILED = 'failed';

    /**
     * Private constructor.
     */
    private function __construct()
    {
    }
}

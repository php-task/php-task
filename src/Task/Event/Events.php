<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Event;

/**
 * Container for all Task events.
 *
 * This class cannot be instantiated.
 */
final class Events
{
    /**
     * Emitted when new new tasks created.
     */
    const TASK_CREATE = 'tasks.create';

    /**
     * Emitted when new new tasks created.
     */
    const TASK_EXECUTION_CREATE = 'tasks.create_execution';

    /**
     * Emitted when task will be started.
     */
    const TASK_BEFORE = 'tasks.before';

    /**
     * Emitted when task will be started.
     */
    const TASK_AFTER = 'tasks.after';

    /**
     * Emitted when after task finished.
     */
    const TASK_FINISHED = 'tasks.finished';

    /**
     * Emitted when task passed.
     */
    const TASK_PASSED = 'tasks.pass';

    /**
     * Emitted when task failed.
     */
    const TASK_FAILED = 'tasks.failed';

    /**
     * Emitted when task will be retried.
     */
    const TASK_RETRIED = 'tasks.retried';

    /**
     * Private constructor.
     */
    private function __construct()
    {
    }
}

<?php
/*
 * This file is part of PHP-Task library.
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
 *
 * @author Johannes Wachter <@wachterjohannes>
 */
final class Events
{
    /**
     * Emitted when new new tasks created.
     */
    const TASK_CREATE = 'tasks.create';

    /**
     * Emitted when task will be started.
     */
    const TASK_BEFORE = 'tasks.before';

    /**
     * Emitted when after task finished.
     */
    const TASK_AFTER = 'tasks.after';

    /**
     * Emitted when task passed.
     */
    const TASK_PASSED = 'tasks.pass';

    /**
     * Emitted when task failed.
     */
    const TASK_FAILED = 'tasks.failed';

    private function __construct()
    {
    }
}

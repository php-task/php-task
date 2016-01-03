<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Storage;

use Task\TaskInterface;

/**
 * Thrown when the requested task not exists in storage.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
class TaskNotExistsException extends \Exception
{
    /**
     * @var TaskInterface
     */
    private $task;

    public function __construct(TaskInterface $task)
    {
        parent::__construct('Task not exists in storage.');

        $this->task = $task;
    }

    /**
     * Returns task which is not handled.
     *
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }
}

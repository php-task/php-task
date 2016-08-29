<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Handler;

/**
 * Interface for task-handler registry.
 */
interface TaskHandlerFactoryInterface
{
    /**
     * Returns task-handle for given class-name.
     *
     * @param string $className
     *
     * @return TaskHandlerInterface
     *
     * @throws TaskHandlerNotExistsException
     */
    public function create($className);
}

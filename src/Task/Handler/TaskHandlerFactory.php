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
 * Task handler factory.
 *
 * Allows to add handler instances to run tasks.
 */
class TaskHandlerFactory implements TaskHandlerFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create($className)
    {
        if (!class_exists($className)) {
            throw new TaskHandlerNotExistsException($className);
        }

        return new $className();
    }
}

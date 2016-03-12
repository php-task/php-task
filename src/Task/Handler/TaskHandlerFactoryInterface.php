<?php
/*
 * This file is part of PHP-Task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Handler;

/**
 * Interface for task handler registry.
 *
 * @author @wachterjohannes <johannes.wachter@massiveart.com>
 */
interface TaskHandlerFactoryInterface
{
    /**
     * @param string $className
     *
     * @return TaskHandlerInterface
     *
     * @throws TaskHandlerNotExistsException
     */
    public function create($className);
}

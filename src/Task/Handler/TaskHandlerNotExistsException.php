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
 * Thrown when the requested handler not exists.
 */
class TaskHandlerNotExistsException extends \Exception
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        parent::__construct(sprintf('Handler with name "%s" not exists.', $className));

        $this->className = $className;
    }

    /**
     * Returns name of requested handler.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}

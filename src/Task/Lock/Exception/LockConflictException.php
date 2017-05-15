<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Lock\Exception;

/**
 * Will be thrown when a conflict is detected:
 *  + Already acquired key was acquired.
 *  + Not acquired key was released.
 *  + Not acquired key was refreshed.
 */
class LockConflictException extends \Exception
{
    /**
     * @var string
     */
    private $key;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Returns key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
}

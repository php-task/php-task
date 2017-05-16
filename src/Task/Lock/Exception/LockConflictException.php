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
 * Base exception for lock-conflicts.
 */
abstract class LockConflictException extends \Exception
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

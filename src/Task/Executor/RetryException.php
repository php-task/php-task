<?php

/*
 * This file is part of php-task library.
 *
 * (c) php-task
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Task\Executor;

/**
 * Internal exception to indicate a retry for given exception.
 */
class RetryException extends \Exception
{
    /**
     * @var int
     */
    private $maximumAttempts;

    /**
     * @param int $maximumAttempts
     * @param \Exception $previous
     */
    public function __construct($maximumAttempts, \Exception $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);

        $this->maximumAttempts = $maximumAttempts;
    }

    /**
     * Returns maximum-attempts.
     *
     * @return int
     */
    public function getMaximumAttempts()
    {
        return $this->maximumAttempts;
    }
}
